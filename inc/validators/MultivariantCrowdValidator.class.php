<?php
/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 31.03.17
 * Time: 15:43
 */
use DBA\AnswerSession;
use DBA\QueryFilter;
use DBA\TwoCompareAnswer;

/**
 * Class CrowdValidator validates a session based on similarities where enough answers were given and match the one the user gave
 */
class MultivariantCrowdValidator extends Validator {
  const DIFF_MALUS_THRESHOLD = 1;
  const DIFF_BONUS_THRESHOLD = 0.4;
  const DIFF_MALUS           = 0.2;
  const DIFF_BONUS           = 0.2;
  
  const CERTAINTY_THRESHOLD = 0.4;
  
  const NAME             = "MultivariantCrowdValidator";
  const EVENT_DIFF_MALUS = "SimilarityDistanceTooLarge";
  const EVENT_DIFF_BONUS = "SimilarityDistanceSmall";
  
  /**
   * @param $answerSession AnswerSession
   * @param $validity float
   * @return float updated validity
   */
  function validateRunning($answerSession, $validity) {
    return $this->validate($answerSession);
  }
  
  /**
   * @param $answerSession AnswerSession
   * @param $validity float
   * @return float updated validity
   */
  function validateFinished($answerSession, $validity) {
    return $this->validate($answerSession);
  }
  
  /**
   * @param $answerSession AnswerSession
   * @param $validity float
   * @param $isFinished bool
   * @return float updated validity
   */
  private function validate($answerSession) {
    global $FACTORIES;
    
    $qF = new QueryFilter(TwoCompareAnswer::ANSWER_SESSION_ID, $answerSession->getId(), "=");
    $twoAnswers = $FACTORIES::getTwoCompareAnswerFactory()->filter(array($FACTORIES::FILTER => $qF));
    $resultSets = array();
    $answers = array();
    foreach ($twoAnswers as $twoAnswer) {
      $resultSets[] = $FACTORIES::getResultTupleFactory()->get($twoAnswer->getResultTupleId());
      $answers[] = $twoAnswer->getAnswer();
    }
    /*$multivariantGaussian = new MultivariantGauss($resultSets, $answerSession);
    
    $probability = $multivariantGaussian->getProbability($answers);
    */
    
    $sum = 0;
    $count = 0;
    for($i=0;$i<sizeof($resultSets);$i++){
      $gaussian = new SimpleGauss($resultSets[$i], $answerSession);
      if($gaussian->isValid()){
        $count++;
        $sum += pow($gaussian->getProbability($answers[$i]));
      }
    }
    
    $probability = 0;
    if($count > 0) {
      $probability = $sum / $count;
    }
    
    if ($probability < 0) {
      $probability = 0;
    }
    else if ($probability > 1) {
      $probability = 1;
    }
    return $probability;
  }
}

$VALIDATORS[] = new CrowdValidator();