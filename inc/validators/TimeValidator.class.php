<?php
/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 31.03.17
 * Time: 12:35
 */
use DBA\AnswerSession;
use DBA\OrderFilter;
use DBA\QueryFilter;
use DBA\TwoCompareAnswer;

/**
 * Class TimeValidator
 *
 * This class will only validate sessions when the last question is finished
 */
class TimeValidator extends Validator {
  const AVG_TIME_LOWER_LIMIT = 1.5;
  const PEAK_DIST_FROM_AVG   = 60;
  const AVG_TIME_UPPER_LIMIT = 100;
  
  const MALUS_TOO_FAST  = 0.5;
  const MALUS_PEAK_TIME = 0.2;
  const MALUS_TOO_SLOW  = 0.4;
  
  function validateRunning($answerSession, $validity) {
    return $validity;
  }
  
  /**
   * @param $answerSession AnswerSession
   * @param $validity float
   * @return float
   */
  function validateFinished($answerSession, $validity) {
    global $FACTORIES;
    
    $qF = new QueryFilter(TwoCompareAnswer::ANSWER_SESSION_ID, $answerSession->getId(), "=");
    $oF = new OrderFilter(TwoCompareAnswer::TIME, "ASC");
    $twoAnswers = $FACTORIES::getTwoCompareAnswerFactory()->filter(array($FACTORIES::FILTER => $qF, $FACTORIES::ORDER => $oF));
    
    if (sizeof($twoAnswers) < 2) {
      return $validity;
    }
    
    $totalTime = 0;
    $times = array();
    $min = PHP_INT_MAX;
    $max = PHP_INT_MIN;
    $lastTime = 0;
    foreach ($twoAnswers as $answer) {
      if ($lastTime == 0) {
        $lastTime = $answer->getTime();
        continue;
      }
      $delta = $answer->getTime() - $lastTime;
      $lastTime = $answer->getTime();
      $times[] = $delta;
      
      $totalTime += $delta;
      if ($delta > $max) {
        $max = $delta;
      }
      else if ($delta < $min) {
        $min = $delta;
      }
    }
    
    // test if average time is too fast
    $avgTime = $totalTime / (sizeof($twoAnswers) - 1);
    if ($avgTime < TimeValidator::AVG_TIME_LOWER_LIMIT) {
      $validity *= 1 - TimeValidator::MALUS_TOO_FAST;
    }
    
    if ($answerSession->getMicroworkerId() == null) {
      return $validity; // time patterns are only tested for microworkers
    }
    
    // test if specific patterns occured in time
    if (abs($avgTime - $max) >= TimeValidator::PEAK_DIST_FROM_AVG) {
      $validity *= 1 - TimeValidator::MALUS_PEAK_TIME;
    }
    if ($avgTime >= TimeValidator::AVG_TIME_UPPER_LIMIT) {
      $validity *= 1 - TimeValidator::MALUS_TOO_SLOW;
    }
    
    // TODO: if possible try to detect cyclic time patterns
    
    return $validity;
  }
}

$VALIDATORS[] = new TimeValidator();