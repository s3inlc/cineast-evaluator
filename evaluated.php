<?php

/** @var Login $LOGIN */
use DBA\JoinFilter;
use DBA\OrderFilter;
use DBA\Query;
use DBA\QueryFilter;
use DBA\QueryResultTuple;
use DBA\ResultTuple;
use DBA\TwoCompareAnswer;

/** @var array $OBJECTS */

require_once(dirname(__FILE__) . "/inc/load.php");
if (!$LOGIN->isLoggedin()) {
  header("Location: admin.php?err=4" . time());
  die();
}
$TEMPLATE = new Template("content/evaluated");

$MENU->setActive("evaluated");
$OBJECTS['pageTitle'] = "Cineast Evaluator";
$OBJECTS['administrator'] = true;

if(isset($_POST['action'])){
  //
}

$qF = new QueryFilter(ResultTuple::SIGMA, 0, ">=");
$oF = new OrderFilter(ResultTuple::SIGMA, "ASC");
$resultTuples = $FACTORIES::getResultTupleFactory()->filter(array($FACTORIES::FILTER => $qF, $FACTORIES::ORDER => $oF));

$numAnswers = new DataSet();
$queries = new DataSet();
foreach($resultTuples as $resultTuple){
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

echo $TEMPLATE->render($OBJECTS);