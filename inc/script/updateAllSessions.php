<?php
/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 05.04.17
 * Time: 17:04
 */

require_once(dirname(__FILE__) . "/../load.php");

/** @var $VALIDATORS Validator[] */

$sessions = $FACTORIES::getAnswerSessionFactory()->filter(array());
foreach ($sessions as $session) {
  $currentValidity = 0;
  foreach ($VALIDATORS as $validator) {
    if ($this->answerSession->getIsOpen() == 1) {
      //
    }
    else {
      $currentValidity = $validator->validateFinished($this->answerSession, $currentValidity);
    }
  }
  $session->setCurrentValidity($currentValidity);
  $FACTORIES::getAnswerSessionFactory()->update($session);
}




