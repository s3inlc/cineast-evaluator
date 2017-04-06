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
use DBA\Validation;

/**
 * Class CrowdValidator validates a session based on similarities where enough answers were given and match the one the user gave
 */
class CrowdValidator extends Validator {
  const DIFF_MALUS_THRESHOLD = 1;
  const DIFF_BONUS_THRESHOLD = 0.4;
  const DIFF_MALUS           = 0.2;
  const DIFF_BONUS           = 0.2;
  
  const CERTAINTY_THRESHOLD = 0.4;
  
  const NAME             = "CrowdValidator";
  const EVENT_DIFF_MALUS = "SimilarityDistanceTooLarge";
  const EVENT_DIFF_BONUS = "SimilarityDistanceSmall";
  
  /**
   * @param $answerSession AnswerSession
   * @param $validity float
   * @return float updated validity
   */
  function validateRunning($answerSession, $validity) {
    return $this->validate($answerSession, $validity, false);
  }
  
  /**
   * @param $answerSession AnswerSession
   * @param $validity float
   * @return float updated validity
   */
  function validateFinished($answerSession, $validity) {
    return $this->validate($answerSession, $validity, true);
  }
  
  /**
   * @param $answerSession AnswerSession
   * @param $validity float
   * @param $isFinished bool
   * @return float updated validity
   */
  private function validate($answerSession, $validity, $isFinished) {
    global $FACTORIES;
    
    /*$qF = new QueryFilter(TwoCompareAnswer::ANSWER_SESSION_ID, $answerSession->getId(), "=");
    $twoAnswers = $FACTORIES::getTwoCompareAnswerFactory()->filter(array($FACTORIES::FILTER => $qF));
    foreach ($twoAnswers as $twoAnswer) {
      // for every answer we are testing how good it is compared to all other answers
      $resultTuple = $FACTORIES::getResultTupleFactory()->get($twoAnswer->getResultTupleId());
      $diff = abs($resultTuple->getSimilarity() - $twoAnswer->getAnswer());
      if ($resultTuple->getCertainty() < CrowdValidator::CERTAINTY_THRESHOLD) {
        continue;
      }
      else if ($diff > CrowdValidator::DIFF_MALUS_THRESHOLD) {
        $validity -= CrowdValidator::DIFF_MALUS;
        $validity *= $resultTuple->getCertainty();
        if ($isFinished) {
          $entry = new Validation(0, $answerSession->getId(), $this::NAME, $this::EVENT_DIFF_MALUS, 0, $this::DIFF_MALUS);
          $FACTORIES::getValidationFactory()->save($entry);
        }
      }
      else if ($diff < CrowdValidator::DIFF_BONUS_THRESHOLD) {
        $validity += CrowdValidator::DIFF_BONUS;
        $validity *= 1 / $resultTuple->getCertainty();
        if ($isFinished) {
          $entry = new Validation(0, $answerSession->getId(), $this::NAME, $this::EVENT_DIFF_BONUS, $this::DIFF_BONUS, 0);
          $FACTORIES::getValidationFactory()->save($entry);
        }
      }
    }
    if ($validity < 0) {
      $validity = 0;
    }
    else if ($validity > 1) {
      $validity = 1;
    }*/
    return $validity;
  }
}

//$VALIDATORS[] = new CrowdValidator();