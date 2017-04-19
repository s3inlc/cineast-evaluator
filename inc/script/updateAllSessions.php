<?php
/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 05.04.17
 * Time: 17:04
 */

use DBA\QueryFilter;
use DBA\ThreeCompareAnswer;
use DBA\TwoCompareAnswer;
use DBA\Validation;

require_once(dirname(__FILE__) . "/../load.php");

/** @var $VALIDATORS Validator[] */

/*
 * IMPORTANT:
 * This script is only for experimental purposes and should not be called regularly.
 *
 * It can negatively affect the validity of the evaluation results when running this!
 */

$sessions = $FACTORIES::getAnswerSessionFactory()->filter(array());
foreach ($sessions as $session) {
  $currentValidity = 0;
  if ($session->getIsOpen() == 0) {
    // delete validation actions for the ones we update now
    $qF = new QueryFilter(Validation::ANSWER_SESSION_ID, $session->getId(), "=");
    $FACTORIES::getValidationFactory()->massDeletion(array($FACTORIES::FILTER => $qF));
    
    foreach ($VALIDATORS as $validator) {
      $currentValidity = $validator->validateFinished($session, $currentValidity);
    }
  }
  else{
    if(time() - $session->getTimeOpened() >= SESSION_TIMEOUT){
      $qF = new QueryFilter(TwoCompareAnswer::ANSWER_SESSION_ID, $session->getId(), "=");
      $count = $FACTORIES::getTwoCompareAnswerFactory()->countFilter(array($FACTORIES::FILTER => $qF));
      $qF = new QueryFilter(ThreeCompareAnswer::ANSWER_SESSION_ID, $session->getId(), "=");
      $count += $FACTORIES::getThreeCompareAnswerFactory()->countFilter(array($FACTORIES::FILTER => $qF));
      if($count == 0){
        // TODO: should we really delete here?
        $FACTORIES::getAnswerSessionFactory()->delete($session);
        continue;
      }
      else{
        $session->setIsOpen(0);
        foreach ($VALIDATORS as $validator) {
          $currentValidity = $validator->validateFinished($session, $currentValidity);
        }
      }
    }
  }
  
  if ($session->getUserId() != null) {
    $currentValidity = 1; // set for admin users
  }
  $session->setCurrentValidity($currentValidity);
  $FACTORIES::getAnswerSessionFactory()->update($session);
}




