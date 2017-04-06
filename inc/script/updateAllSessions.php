<?php
/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 05.04.17
 * Time: 17:04
 */

use DBA\QueryFilter;
use DBA\Validation;

require_once(dirname(__FILE__) . "/../load.php");

/** @var $VALIDATORS Validator[] */

$sessions = $FACTORIES::getAnswerSessionFactory()->filter(array());
foreach ($sessions as $session) {
  $currentValidity = 0;
  foreach ($VALIDATORS as $validator) {
    if ($session->getIsOpen() == 1) {
      //
    }
    else {
      // delete validation actions for the ones we update now
      $qF = new QueryFilter(Validation::ANSWER_SESSION_ID, $session->getId(), "=");
      $FACTORIES::getValidationFactory()->massDeletion(array($FACTORIES::FILTER => $qF));
      
      $currentValidity = $validator->validateFinished($session, $currentValidity);
    }
  }
  if ($session->getUserId() != null) {
    $currentValidity = 1; // set for admin users
  }
  $session->setCurrentValidity($currentValidity);
  $FACTORIES::getAnswerSessionFactory()->update($session);
}




