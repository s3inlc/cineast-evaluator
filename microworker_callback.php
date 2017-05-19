<?php

use DBA\Microworker;
use DBA\QueryFilter;

require_once(dirname(__FILE__) . "/inc/load.php");

$MTURK = new MTurk();

if ($MTURK->isMechanicalTurk()) {
  // continue with current session or close session if it's finished
  header("Location: session.php");
  die();
}
else if (isset($_GET['token'])) {
  // start a new microworker
  $qF = new QueryFilter(Microworker::TOKEN, $_GET['token'], "=");
  $microworker = $FACTORIES::getMicroworkerFactory()->filter(array($FACTORIES::FILTER => $qF), true);
  if ($microworker == null) {
    // TODO: show error
    die("Invalid token!");
  }
  else if ($microworker->getTimeClosed() != 0 && $microworker->getTimeStarted() != 0) {
    // TODO: handle closed
    die("Already finished!");
  }
  else if ($microworker->getIsLocked() == 1) {
    // TODO: handle locked
    die("Currently not available!");
  }
  else {
    // we can create a session
    $microworker->setTimeStarted(time());
    $FACTORIES::getMicroworkerFactory()->update($microworker);
    $_SESSION['microworkerId'] = $microworker->getId();
    unset($_SESSION['answerSessionId']);
    header("Location: session.php");
  }
}
else {
  // TODO: Error
  die("Invalid access!");
}

