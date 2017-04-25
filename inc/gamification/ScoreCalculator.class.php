<?php
use DBA\AnswerSession;
use DBA\QueryFilter;
use DBA\TwoCompareAnswer;

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 25.04.17
 * Time: 14:21
 */
class ScoreCalculator {
  private $answerSession;
  
  const SCORE_BASE  = "baseScore";
  const SCORE_TOTAL = "totalScore";
  
  const GAUSSIAN_CONST_MULT = 1;
  const BASIC_CONST_MULT    = 1.1;
  
  /**
   * ScoreCalculator constructor.
   * @param $answerSession AnswerSession
   */
  public function __construct($answerSession) {
    $this->answerSession = $answerSession;
  }
  
  /**
   * @return int[]
   */
  public function getScore() {
    global $FACTORIES;
    
    $totalScore = array();
    
    $qF = new QueryFilter(TwoCompareAnswer::ANSWER_SESSION_ID, $this->answerSession->getId(), "=");
    $answers = $FACTORIES::getTwoCompareAnswerFactory()->filter(array($FACTORIES::FILTER => $qF));
    
    $score = sizeof($answers);
    foreach ($answers as $answer) {
      $tuple = $FACTORIES::getResultTupleFactory()->get($answer->getResultTupleId());
      $gaussian = new SimpleGauss($tuple, $this->answerSession);
      if ($gaussian->isValid()) {
        $score *= ScoreCalculator::GAUSSIAN_CONST_MULT + $gaussian->getProbability($answer->getAnswer());
      }
      else {
        $score *= ScoreCalculator::BASIC_CONST_MULT;
      }
    }
    
    $score = floor($score);
    
    $totalScore[ScoreCalculator::SCORE_BASE] = $score;
    
    // TODO: add score for achievements
    
    $totalScore[ScoreCalculator::SCORE_TOTAL] = $score;
    return $totalScore;
  }
}