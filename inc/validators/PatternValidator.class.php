<?php
use DBA\AnswerSession;
use DBA\OrderFilter;
use DBA\QueryFilter;
use DBA\TwoCompareAnswer;
use DBA\Validation;

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 06.04.17
 * Time: 10:26
 */
class PatternValidator extends Validator {
  const SAME_ANSWER_THRESHOLD = 2;
  
  const SAME_ANSWER_MALUS = 0.5;
  
  const NAME              = "PatternValidator";
  const EVENT_SAME_ANSWER = "ManySameAnswers";
  
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
    
    // TestPattern: all the same answer
    $answers = array(0, 0, 0, 0);
    foreach ($twoAnswers as $answer) {
      $answers[$answer->getAnswer()]++;
    }
    for ($i = 0; $i < 4; $i++) {
      if (sizeof($twoAnswers) - $answers[$i] <= PatternValidator::SAME_ANSWER_THRESHOLD) {
        $validity *= 1 - PatternValidator::SAME_ANSWER_MALUS;
        $entry = new Validation(0, $answerSession->getId(), $this::NAME, $this::EVENT_SAME_ANSWER, 0, $this::SAME_ANSWER_MALUS);
        $FACTORIES::getValidationFactory()->save($entry);
      }
    }
    
    // TestPattern: cyclic answering
    // TODO: implement
    
    if ($validity < 0) {
      $validity = 0;
    }
    else if ($validity > 1) {
      $validity = 1;
    }
    
    return $validity;
  }
}

$VALIDATORS[] = new PatternValidator();