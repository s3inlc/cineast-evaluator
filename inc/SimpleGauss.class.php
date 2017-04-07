<?php
use DBA\AnswerSession;
use DBA\JoinFilter;
use DBA\QueryFilter;
use DBA\ResultTuple;
use DBA\TwoCompareAnswer;

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 06.04.17
 * Time: 14:25
 */
class SimpleGauss {
  private $mu;
  private $sigma;
  
  /**
   * SimpleGauss constructor.
   * @param $tuple ResultTuple
   * @param $excludedAnswerSession AnswerSession
   */
  public function __construct($tuple, $excludedAnswerSession = null) {
    global $FACTORIES;
    
    $qF1 = new QueryFilter(TwoCompareAnswer::RESULT_TUPLE_ID, $tuple->getId(), "=");
    $qF2 = new QueryFilter(AnswerSession::IS_OPEN, 0, "=", $FACTORIES::getAnswerSessionFactory()); // only consider completed sessions
    $filters = array($qF1, $qF2);
    if($excludedAnswerSession != null){
      // we need to exclude this answer session
      $qF3 = new QueryFilter(AnswerSession::ANSWER_SESSION_ID, $excludedAnswerSession->getId(), "<>", $FACTORIES::getAnswerSessionFactory());
      $filters[] = $qF3;
    }
    $jF = new JoinFilter($FACTORIES::getAnswerSessionFactory(), TwoCompareAnswer::ANSWER_SESSION_ID, AnswerSession::ANSWER_SESSION_ID);
    $joined = $FACTORIES::getTwoCompareAnswerFactory()->filter(array($FACTORIES::FILTER => $filters, $FACTORIES::JOIN => $jF));
    
    if (sizeof($joined['TwoCompareAnswer']) < GAUSS_LIMIT) { // TODO: set limit which should be used that a gauss curve can be constructed
      $this->mu = -1;
      $this->sigma = -1;
      return;
    }
    
    $weightedSum = 0;
    $probabilitySum = 0;
    for ($i = 0; $i < sizeof($joined['TwoCompareAnswer']); $i++) {
      /** @var $twoCompareAnswer TwoCompareAnswer */
      $twoCompareAnswer = $joined['TwoCompareAnswer'][$i];
      /** @var $answerSession AnswerSession */
      $answerSession = $joined['AnswerSession'][$i];
      
      $weightedSum += $twoCompareAnswer->getAnswer() * $answerSession->getCurrentValidity();
      $probabilitySum += $answerSession->getCurrentValidity();
    }
    
    if ($probabilitySum == 0) {
      $this->mu = -1;
      $this->sigma = -1;
      return;
    }
    
    $this->mu = $weightedSum / $probabilitySum;
    
    $sigmaSum = 0;
    for ($i = 0; $i < sizeof($joined['TwoCompareAnswer']); $i++) {
      /** @var $twoCompareAnswer TwoCompareAnswer */
      $twoCompareAnswer = $joined['TwoCompareAnswer'][$i];
      
      $sigmaSum += ($twoCompareAnswer->getAnswer() - $this->mu) * ($twoCompareAnswer->getAnswer() - $this->mu);
    }
    
    $this->sigma = sqrt($sigmaSum / sizeof($joined['TwoCompareAnswer']));
  }
  
  /**
   * @return float -1 if sigma could not be calculated
   */
  public function getSigma() {
    return $this->sigma;
  }
  
  /**
   * @return float -1 if mu could not be calculated
   */
  public function getMu() {
    return $this->mu;
  }
  
  /**
   * @return bool
   */
  public function isValid() {
    return $this->mu != -1 && $this->sigma != -1;
  }
  
  /**
   * @param $answer int between 0 and 3
   * @return float probability for this answer
   */
  public function getProbability($answer) {
    if (!$this->isValid()) {
      return -1;
    }
    else if($this->sigma == 0){
      if($answer == $this->mu){
        return 1;
      }
      return 0;
    }
    $exponent = -1 / 2 * pow(($answer - $this->mu) / pow($this->sigma, 2), 2);
    return 1 / ($this->sigma * sqrt(2 * pi())) * exp($exponent);
  }
}