<?php
/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 14.07.17
 * Time: 15:43
 */

use DBA\JoinFilter;
use DBA\MediaObject;
use DBA\Query;
use DBA\QueryFilter;
use DBA\QueryResultTuple;
use DBA\ResultTuple;

require_once(dirname(__FILE__) . "/../load.php");

$exportPath = "/var/www/evaluator/export/";

if (!isset($argv[1])) {
  die("You need to provide at least one ID or the term 'ALL' to export!\n");
}

// collect arguments
$allArgs = array();
$index = 1;
while (isset($argv[$index])) {
  $allArgs[] = $argv[$index];
  $index++;
}

echo "Parsed arguments\n";

// get all queries
$queries = $FACTORIES::getQueryFactory()->filter(array());
/** @var $matchingQueries Query[] */
$matchingQueries = array();
foreach ($queries as $query) {
  foreach ($allArgs as $arg) {
    if ($arg == 'ALL') {
      $matchingQueries[] = $query;
    }
    else if ($arg == $query->getId()) {
      $matchingQueries[] = $query;
    }
  }
}

echo "Matched queries\n";
echo "Loading data...\n";

$exports = array();
foreach ($matchingQueries as $query) {
  // get query object
  $qF = new QueryFilter(QueryResultTuple::QUERY_ID, $query->getId(), "=", $FACTORIES::getQueryResultTupleFactory());
  $jF = new JoinFilter($FACTORIES::getQueryResultTupleFactory(), ResultTuple::RESULT_TUPLE_ID, QueryResultTuple::RESULT_TUPLE_ID);
  $joinedResultTuples = $FACTORIES::getResultTupleFactory()->filter(array($FACTORIES::FILTER => $qF, $FACTORIES::JOIN => $jF));
  /** @var $rt ResultTuple */
  $rt = $joinedResultTuples[$FACTORIES::getResultTupleFactory()->getModelName()][0];
  /** @var $resultTuples ResultTuple[] */
  $resultTuples = $joinedResultTuples[$FACTORIES::getResultTupleFactory()->getModelName()];
  $queryObjectId = $rt->getObjectId1();
  if (!isset($exports[$queryObjectId])) {
    $exports[$queryObjectId] = array();
  }
  foreach ($resultTuples as $resultTuple) {
    if (array_search($resultTuple->getId(), $exports[$queryObjectId]) === false) {
      $exports[$queryObjectId][] = $resultTuple->getId();
    }
  }
}

echo "Generating output...\n";

foreach ($exports as $key => $export) {
  $queryMediaObject = $FACTORIES::getMediaObjectFactory()->get($key);
  $exportName = "export_" . $queryMediaObject->getId() . "_" . $queryMediaObject->getChecksum() . ".csv";
  $file = fopen($exportPath . "/" . $exportName, "w");
  if ($file == false) {
    echo "Failed to open write file $exportName!\n";
    continue;
  }
  fputcsv($file, array("filehash", "mu", "sigma", "isFinal"));
  foreach ($export as $tupleId) {
    $qF = new QueryFilter(ResultTuple::RESULT_TUPLE_ID, $tupleId, "=");
    $jF = new JoinFilter($FACTORIES::getMediaObjectFactory(), ResultTuple::OBJECT_ID2, MediaObject::MEDIA_OBJECT_ID);
    $joined = $FACTORIES::getResultTupleFactory()->filter(array($FACTORIES::FILTER => $qF, $FACTORIES::JOIN => $jF));
    for ($i = 0; $i < sizeof($joined[$FACTORIES::getResultTupleFactory()->getModelName()]); $i++) {
      /** @var $resultTuple ResultTuple */
      $resultTuple = $joined[$FACTORIES::getResultTupleFactory()->getModelName()][$i];
      /** @var $mediaObject MediaObject */
      $mediaObject = $joined[$FACTORIES::getMediaObjectFactory()->getModelName()][$i];
      fputcsv($file, array($mediaObject->getChecksum(), $resultTuple->getMu(), $resultTuple->getSigma(), $resultTuple->getIsFinal()));
    }
  }
  fclose($file);
  echo "$exportName completed!\n";
}
echo "done!\n";




