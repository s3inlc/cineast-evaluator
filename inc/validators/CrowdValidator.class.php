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
class CrowdValidator extends Validator {
  const DIFF_MALUS_THRESHOLD = 0.4;
  const DIFF_BONUS_THRESHOLD = 0.2;
  const DIFF_MALUS           = 0.2;
  const DIFF_BONUS           = 0.2;
  
  /**
   * @param $answerSession AnswerSession
   * @param $validity
   * @return float updated validity
   */
  function validateRunning($answerSession, $validity) {
    global $FACTORIES;
    
    $qF = new QueryFilter(TwoCompareAnswer::ANSWER_SESSION_ID, $answerSession->getId(), "=");
    $twoAnswers = $FACTORIES::getTwoCompareAnswerFactory()->filter(array($FACTORIES::FILTER => $qF));
    foreach ($twoAnswers as $twoAnswer) {
      // for every answer we are testing how good it is compared to all other answers
      $resultTuple = $FACTORIES::getResultTupleFactory()->get($twoAnswer->getResultTupleId());
      $diff = abs($resultTuple->getSimilarity() - $twoAnswer->getAnswer() / 3) * $resultTuple->getCertainty();
      if ($diff > CrowdValidator::DIFF_MALUS_THRESHOLD) {
        $validity -= CrowdValidator::DIFF_MALUS;
      }
      else if ($diff < CrowdValidator::DIFF_BONUS_THRESHOLD) {
        $validity += CrowdValidator::DIFF_BONUS;
      }
    }
    if ($validity < 0) {
      $validity = 0;
    }
    else if ($validity > 1) {
      $validity = 1;
    }
    return $validity;
  }
  
  function validateFinished($answerSession, $validity) {
    // TODO: Implement validateFinished() method.
  }
}

$VALIDATORS[] = new CrowdValidator();