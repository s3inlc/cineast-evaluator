<?php

use DBA\JoinFilter;
use DBA\QueryFilter;
use DBA\QueryResultTuple;
use DBA\ResultTuple;

require_once(dirname(__FILE__) . "/../load.php");

// TODO: get which script should be used
// TODO: include the selected similarity calculator

require_once(dirname(__FILE__) . "/similarities/Calculator.class.php");
require_once(dirname(__FILE__) . "/similarities/ExampleCalculator.class.php");
require_once(dirname(__FILE__) . "/similarities/SimilaritySumCalculator.class.php");
require_once(dirname(__FILE__) . "/similarities/SimilarityGaussCalculator.php");

echo "calculating...\n";

$calculator = new SimilarityGaussCalculator();
$qF = new QueryFilter(ResultTuple::IS_FINAL, "0", "=");
$resultSets = $FACTORIES::getResultTupleFactory()->filter(array($FACTORIES::FILTER => $qF));
$changed = array();
$calculator->updateSimilarities($resultSets, $changed);

echo "updating...\n";

$queriesToTest = array();

foreach ($resultSets as $resultSet) {
  if (isset($changed[$resultSet->getId()])) {
    $FACTORIES::getResultTupleFactory()->update($resultSet);
    $qF = new QueryFilter(QueryResultTuple::RESULT_TUPLE_ID, $resultSet->getId(), "=");
    $queryResultTuples = $FACTORIES::getQueryResultTupleFactory()->filter(array($FACTORIES::FILTER => $qF));
    foreach ($queryResultTuples as $queryResultTuple) {
      if (!in_array($queryResultTuple->getQueryId(), $queriesToTest)) {
        $queriesToTest[] = $queryResultTuple->getQueryId();
      }
    }
  }
}

echo "checking for finished queries...\n";

foreach ($queriesToTest as $queryId) {
  $query = $FACTORIES::getQueryFactory()->get($queryId);
  if ($query->getIsClosed() == 1) {
    continue; // query is already finished
  }
  $qF = new QueryFilter(QueryResultTuple::QUERY_ID, $queryId, "=", $FACTORIES::getQueryResultTupleFactory());
  $jF = new JoinFilter($FACTORIES::getQueryResultTupleFactory(), ResultTuple::RESULT_TUPLE_ID, QueryResultTuple::RESULT_TUPLE_ID);
  $joined = $FACTORIES::getResultTupleFactory()->filter(array($FACTORIES::FILTER => $qF, $FACTORIES::JOIN => $jF));
  $fullyEvaluated = true;
  for ($i = 0; $i < sizeof($joined[$FACTORIES::getResultTupleFactory()->getModelName()]); $i++) {
    /** @var $resultTuple ResultTuple */
    $resultTuple = $joined[$FACTORIES::getResultTupleFactory()->getModelName()][$i];
    if ($resultTuple->getIsFinal() == 0) {
      $fullyEvaluated = false;
      break;
    }
  }
  if ($fullyEvaluated) {
    // all tuples of this query are final and therefore we can close the query
    $query->setIsClosed(1);
    $FACTORIES::getQueryFactory()->update($query);
  }
}

echo "finished!\n";