<?php

/** @var $OBJECTS array */

use DBA\QueryFilter;
use DBA\ResultTuple;

require_once(dirname(__FILE__) . "/inc/load.php");
$TEMPLATE = new Template("content/admin");

$MENU->setActive("admin");
$OBJECTS['pageTitle'] = "Cineast Evaluator";
$OBJECTS['administrator'] = true;

if (isset($_GET['err'])) {
  $errNum = substr($_GET['err'], 0, 1);
  $errTime = substr($_GET['err'], 1);
  if (time() - $errTime < 60) {
    switch ($errNum) {
      case 1:
        $errorMessage = "You need to fill in all fields!";
        break;
      case 2:
        $errorMessage = "Fields cannot be empty!";
        break;
      case 3:
        $errorMessage = "Invalid username/password!";
        break;
      case 4:
        $errorMessage = "You were logged out due to inactivity!";
        break;
      default:
        $errorMessage = "An unknown error happened!";
        break;
    }
    UI::addErrorMessage($errorMessage);
  }
}

if (isset($_GET['logout'])) {
  UI::addSuccessMessage("You logged out successfully!");
}

if ($LOGIN->isLoggedin()) {
  // get number of pruned tuples
  $result = $FACTORIES::getAnswerSessionFactory()->getDB()->query("SELECT count(*) AS pruned FROM `ResultTuple` WHERE isFinal=1 AND (SELECT count(*) FROM TwoCompareAnswer WHERE ResultTuple.resultTupleId=TwoCompareAnswer.resultTupleId) <= 3");
  $answer = $result->fetchAll()[0];
  $OBJECTS['prunedTuples'] = $answer['pruned'];
  
  // get number of total tuples
  $OBJECTS['totalTuples'] = $FACTORIES::getResultTupleFactory()->countFilter(array());
  
  // get number of final tuples
  $qF = new QueryFilter(ResultTuple::IS_FINAL, 1, "=");
  $OBJECTS['finalTuples'] = $FACTORIES::getResultTupleFactory()->countFilter(array($FACTORIES::FILTER => $qF)) - $OBJECTS['prunedTuples'];
  
  // get number of tuples which have no or not enough data
  $qF = new QueryFilter(ResultTuple::MU, -1, "=");
  $OBJECTS['emptyTuples'] = $FACTORIES::getResultTupleFactory()->countFilter(array($FACTORIES::FILTER => $qF));
  
  // get number of tuples which have some data (gaussian)
  $OBJECTS['incompleteTuples'] = $OBJECTS['totalTuples'] - $OBJECTS['finalTuples'] - $OBJECTS['emptyTuples'] - $OBJECTS['prunedTuples'];
}

echo $TEMPLATE->render($OBJECTS);