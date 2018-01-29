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

$FILES = array("full" => array(), "minimum" => array());

// build file with hash -> filename association
$mediaObjectHashes = array();
$assocFile = fopen($exportPath . "/associations.csv", "w");
$FILES["all"][] = $exportPath . "associations.csv";
$FILES["minimum"][] = $exportPath . "associations.csv";
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
$saved = array();

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
    $exportData[$queryObject] = fopen($exportPath . $queryObject . "_data.csv", "w");
    $FILES["minimum"][] = $exportPath . $queryObject . "_raw.csv";
    $FILES["all"][] = $exportPath . $queryObject . "_raw.csv";
    $FILES["all"][] = $exportPath . $queryObject . "_data.csv";
    fputs($exportRaw[$queryObject], "MediaObject,User,Answer\n");
    fputs($exportData[$queryObject], "MediaObject,Mu,Sigma\n");
    $queryObjects[] = $queryObject;
  }
  foreach ($resultTuples as $resultTuple) {
    $tuples[$queryObject][$resultTuple->getId()] = $resultTuple;
    if (isset($mediaObjectHashes[$resultTuple->getObjectId2()]) && !isset($saved[$resultTuple->getId()])) {
      if ($resultTuple->getMu() != -1) {
        fputs($exportData[$queryObject], $mediaObjectHashes[$resultTuple->getObjectId2()] . "," . $resultTuple->getMu() . "," . $resultTuple->getSigma() . "\n");
      }
      $saved[$resultTuple->getId()] = true;
    }
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


// package as tar gz
// minimum
system("7z a " . $finalZipPath . "nightlyMinimum.7z.new " . implode(" ", $FILES['minimum']));
rename($finalZipPath . "nightlyMinimum.7z.new", $finalZipPath . "nightlyMinimum.7z");
// full
system("7z a " . $finalZipPath . "nightlyFull.7z.new " . implode(" ", $FILES['all']));
rename($finalZipPath . "nightlyFull.7z.new", $finalZipPath . "nightlyFull.7z");















