<?php

use DBA\JoinFilter;
use DBA\MediaObject;
use DBA\OrderFilter;
use DBA\QueryFilter;
use DBA\QueryResultTuple;
use DBA\ResultTuple;

require_once(dirname(__FILE__) . "/../load.php");

// CONFIG section

$exportPath = "exp/";
$finalZipPath = "./";
if (!file_exists($exportPath)) {
  mkdir($exportPath);
}

// end CONFIG section

// build file with hash -> filename association
$mediaObjectHashes = array();
$assocFile = fopen($exportPath . "/associations.csv", "w");
fputs($assocFile, "Checksum,Filepath\n");

$qF = new QueryFilter(MediaObject::ORIGINAL, "", "<>");
$oF = new OrderFilter(MediaObject::CHECKSUM, "ASC");
$mediaObjects = $FACTORIES::getMediaObjectFactory()->filter(array($FACTORIES::FILTER => $qF, $FACTORIES::ORDER => $oF));
foreach ($mediaObjects as $mediaObject) {
  $mediaObjectHashes[$mediaObject->getId()] = $mediaObject->getChecksum();
  fputs($assocFile, $mediaObject->getChecksum() . "," . $mediaObject->getOriginal() . "\n");
}
fclose($assocFile);

// build file with all answers
$uniqueIdCount = 0;
// load all users first
$USERS = array();
$users = $FACTORIES::getUserFactory()->filter(array());
foreach ($users as $user) {
  $USERS[$user->getId()] = $uniqueIdCount++;
}
unset($users);

// load all players
$PLAYERS = array();
$players = $FACTORIES::getPlayerFactory()->filter(array());
foreach ($players as $player) {
  $PLAYERS[$player->getId()] = $uniqueIdCount++;
}
unset($players);

// load all microworkers
$MICROWORKERS = array();
$uniqueCheck = array();
$microworkers = $FACTORIES::getMicroworkerFactory()->filter(array());
foreach ($microworkers as $microworker) {
  $wid = $microworker->getWorkerId();
  if (strlen($wid) == 0) {
    $MICROWORKERS[$microworker->getId()] = $uniqueIdCount++;
  }
  else if (isset($uniqueCheck[$wid])) {
    $MICROWORKERS[$microworker->getId()] = $uniqueCheck[$wid];
  }
  else {
    $uniqueCheck[$wid] = $uniqueIdCount++;
    $MICROWORKERS[$microworker->getId()] = $uniqueCheck[$wid];
  }
}
unset($microworkers);

$exportRaw = array();
$exportData = array();
$tuples = array();

// load queries and tuples
$queries = $FACTORIES::getQueryFactory()->filter(array());
$queryObjects = array();
foreach ($queries as $query) {
  // load all tuples of this query
  $qF = new QueryFilter(QueryResultTuple::QUERY_ID, $query->getId(), "=", $FACTORIES::getQueryResultTupleFactory());
  $jF = new JoinFilter($FACTORIES::getQueryResultTupleFactory(), ResultTuple::RESULT_TUPLE_ID, QueryResultTuple::RESULT_TUPLE_ID);
  $join = $FACTORIES::getResultTupleFactory()->filter(array($FACTORIES::FILTER => $qF, $FACTORIES::JOIN => $jF));
  
  /** @var $resultTuples ResultTuple[] */
  $resultTuples = $join[$FACTORIES::getResultTupleFactory()->getModelName()];
  $queryObject = $mediaObjectHashes[$resultTuples[0]->getObjectId1()];
  if (!isset($tuples[$queryObject])) {
    $tuples[$queryObject] = array();
    $exportRaw[$queryObject] = fopen($exportPath . $queryObject . "_raw.csv", "w");
    $exportRaw[$queryObject] = fopen($exportPath . $queryObject . "_data.csv", "w");
    fputs($exportRaw[$queryObject], "MediaObject,User,Answer\n");
    fputs($exportData[$queryObject], "MediaObject,Similarity,Certainty\n");
    $queryObjects[] = $queryObject;
  }
  foreach ($resultTuples as $resultTuple) {
    $tuples[$queryObject][$resultTuple->getId()] = $resultTuple;
    fputs($exportData[$queryObject], $resultTuple->getObjectId2() . "," . $resultTuple->getMu() . "," . $resultTuple->getSigma() . "\n");
  }
}

// load all answers
$answers = $FACTORIES::getTwoCompareAnswerFactory()->filter(array());
foreach ($answers as $answer) {
  $answerSession = $FACTORIES::getAnswerSessionFactory()->get($answer->getAnswerSessionId());
  foreach ($queryObjects as $queryObject) {
    if (isset($tuples[$queryObject][$answer->getResultTupleId()])) {
      // this answer should be included in the query export
      /** @var $tuple ResultTuple */
      $tuple = $tuples[$queryObject][$answer->getResultTupleId()];
      $id = 0;
      if ($answerSession->getUserId() != 0) {
        $id = $USERS[$answerSession->getUserId()];
      }
      else if ($answerSession->getMicroworkerId() != 0) {
        $id = $MICROWORKERS[$answerSession->getMicroworkerId()];
      }
      else if ($answerSession->getPlayerId() != 0) {
        $id = $PLAYERS[$answerSession->getPlayerId()];
      }
      if (isset($mediaObjectHashes[$tuple->getObjectId2()])) {
        fputs($exportRaw[$queryObject], $mediaObjectHashes[$tuple->getObjectId2()] . "," . $id . "," . $answer->getAnswer() . "\n");
      }
    }
  }
}

foreach ($exportRaw as $exp) {
  fclose($exp);
}

foreach ($exportData as $exp) {
  fclose($exp);
}

















