<?php

use DBA\JoinFilter;
use DBA\OrderFilter;
use DBA\Query;
use DBA\QueryFilter;
use DBA\QueryResultTuple;
use DBA\ResultTuple;
use DBA\TwoCompareAnswer;

/** @var array $OBJECTS */
/** @var Login $LOGIN */

require_once(dirname(__FILE__) . "/inc/load.php");
if (!$LOGIN->isLoggedin()) {
  header("Location: admin.php?err=4" . time());
  die();
}
$TEMPLATE = new Template("content/evaluated/index");

$MENU->setActive("evaluated");
$OBJECTS['pageTitle'] = "Cineast Evaluator";
$OBJECTS['administrator'] = true;

if (isset($_POST['action'])) {
  //
}

if (isset($_GET['view'])) {
  $resultTuple = $FACTORIES::getResultTupleFactory()->get($_GET['view']);
  if ($resultTuple == null) {
    UI::addErrorMessage("Invalid Tuple!");
  }
  else {
    $TEMPLATE = new Template("content/evaluated/tuple");
    $mediaObject1 = $FACTORIES::getMediaObjectFactory()->get($resultTuple->getObjectId1());
    $mediaObject2 = $FACTORIES::getMediaObjectFactory()->get($resultTuple->getObjectId2());
    Util::prepare2CompareQuestion($mediaObject1, $mediaObject2, false);
    
    $imageData = false;
    if ($resultTuple->getSigma() >= 0) {
      if ($resultTuple->getSigma() == 0) {
        // so we can at least draw something
        $resultTuple->setSigma(0.01);
      }
      $imageData = SimpleGauss::generateCurve($resultTuple->getSigma(), $resultTuple->getMu());
    }
    $OBJECTS['imageData'] = $imageData;
  }
}
else if (isset($_GET['queryId'])) {
  $TEMPLATE = new Template("content/evaluated/query");
  $query = $FACTORIES::getQueryFactory()->get($_GET['queryId']);
  if ($query == null) {
    UI::addErrorMessage("Invalid query!");
  }
  else {
    $qF1 = new QueryFilter(ResultTuple::IS_FINAL, "1", "=");
    $qF2 = new QueryFilter(QueryResultTuple::QUERY_ID, $query->getId(), "=");
    $jF = new JoinFilter($FACTORIES::getQueryResultTupleFactory(), ResultTuple::RESULT_TUPLE_ID, QueryResultTuple::RESULT_TUPLE_ID);
    $joined = $FACTORIES::getResultTupleFactory()->filter(array($FACTORIES::FILTER => array($qF1, $qF2), $FACTORIES::JOIN => $jF));
    $OBJECTS['tuples'] = $joined[$FACTORIES::getResultTupleFactory()->getModelName()];
  }
}
else {
  $qF = new QueryFilter(ResultTuple::SIGMA, 0, ">=");
  $oF = new OrderFilter(ResultTuple::SIGMA, "ASC");
  $resultTuples = $FACTORIES::getResultTupleFactory()->filter(array($FACTORIES::FILTER => $qF, $FACTORIES::ORDER => $oF));
  
  $numAnswers = new DataSet();
  $queries = new DataSet();
  foreach ($resultTuples as $resultTuple) {
    // get number of answers for tuple
    $qF = new QueryFilter(TwoCompareAnswer::RESULT_TUPLE_ID, $resultTuple->getId(), "=");
    $count = $FACTORIES::getTwoCompareAnswerFactory()->countFilter(array($FACTORIES::FILTER => $qF));
    $numAnswers->addValue($resultTuple->getId(), $count);
    
    // get queries for this tuple
    $qF = new QueryFilter(QueryResultTuple::RESULT_TUPLE_ID, $resultTuple->getId(), "=");
    $jF = new JoinFilter($FACTORIES::getQueryResultTupleFactory(), Query::QUERY_ID, QueryResultTuple::QUERY_ID);
    $joined = $FACTORIES::getQueryFactory()->filter(array($FACTORIES::FILTER => $qF, $FACTORIES::JOIN => $jF));
    $queries->addValue($resultTuple->getId(), $joined['Query']);
  }
  
  $OBJECTS['resultTuples'] = $resultTuples;
  $OBJECTS['queries'] = $queries;
  $OBJECTS['numAnswers'] = $numAnswers;
}

echo $TEMPLATE->render($OBJECTS);