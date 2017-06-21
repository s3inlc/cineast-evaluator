<?php

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
   * @param bool $history
   * @return float updated validity
   */
  function validateFinished($answerSession, $validity, $history = false) {
    return $this->validate($answerSession, $history);
  }
  
  /**
   * @param $answerSession AnswerSession
   * @param bool $history
   * @return float updated validity
   * @internal param float $validity
   * @internal param bool $isFinished
   */
  private function validate($answerSession, $history = false) {
    global $FACTORIES;
    
    $qF = new QueryFilter(TwoCompareAnswer::ANSWER_SESSION_ID, $answerSession->getId(), "=");
    $twoAnswers = $FACTORIES::getTwoCompareAnswerFactory()->filter(array($FACTORIES::FILTER => $qF));
    $resultSets = array();
    $answers = array();
    foreach ($twoAnswers as $twoAnswer) {
      $resultSets[] = $FACTORIES::getResultTupleFactory()->get($twoAnswer->getResultTupleId());
      $answers[] = $twoAnswer->getAnswer();
    }
    
    $sum = 0;
    $factor = 1;
    $count = 0;
    for ($i = 0; $i < sizeof($resultSets); $i++) {
      $gaussian = new SimpleGauss($resultSets[$i], $answerSession);
      if ($gaussian->isValid()) {
        $count++;
        $prob = pow($gaussian->getProbability($answers[$i]), 6);
        if ($gaussian->getSigma() > 1 && abs($answers[$i] - $gaussian->getMu()) < 1 && $prob > 0.5) {
          $prob *= 8;
        }
        else if ($gaussian->getSigma() > 0.5 && abs($answers[$i] - $gaussian->getMu()) < 1 && $prob > 0.5) {
          $prob *= 4;
        }
        else if ($gaussian->getSigma() > 0.25 && abs($answers[$i] - $gaussian->getMu()) < 0.5 && $prob > 0.5) {
          $prob *= 2;
        }
        $factor *= 0.6 + (min($prob, 1));
        if ($history) {
          echo "Current validity: " . $factor . "\n";
        }
        $sum += $prob;
      }
    }
    
    /*$probability = 0;
    if ($count > 0) {
      $probability = $sum / $count;
    }*/
    
    return max(0, min(1, $factor));
    
    //return $probability;
  }
}

$VALIDATORS[] = new CrowdValidator();