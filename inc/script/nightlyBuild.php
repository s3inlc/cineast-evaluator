<?php

use DBA\JoinFilter;
use DBA\MediaObject;
use DBA\Microworker;
use DBA\OrderFilter;
use DBA\QueryFilter;
use DBA\QueryResultTuple;
use DBA\ResultTuple;
use DBA\TwoCompareAnswer;

require_once(dirname(__FILE__) . "/../load.php");

// CONFIG section

$exportPath = "exp/";
$finalZipPath = "./";
mkdir($exportPath);

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

$export = array();
$tuples = array();
$first = array();

// load queries and tuples
$queries = $FACTORIES::getQueryFactory()->filter(array());
foreach ($queries as $query) {
  $export[$query->getId()] = fopen($exportPath . $query->getDisplayName() . ".csv", "w");
  $first[$query->getId()] = true;
  // load all tuples of this query
  $qF = new QueryFilter(QueryResultTuple::QUERY_ID, $query->getId(), "=", $FACTORIES::getQueryResultTupleFactory());
  $jF = new JoinFilter($FACTORIES::getQueryResultTupleFactory(), ResultTuple::RESULT_TUPLE_ID, QueryResultTuple::RESULT_TUPLE_ID);
  $join = $FACTORIES::getResultTupleFactory()->filter(array($FACTORIES::FILTER => $qF, $FACTORIES::JOIN => $jF));
  
  /** @var $resultTuples ResultTuple[] */
  $resultTuples = $join[$FACTORIES::getResultTupleFactory()->getModelName()];
  $allTuples = array();
  foreach ($resultTuples as $resultTuple) {
    $allTuples[$resultTuple->getId()] = $resultTuple;
  }
  
  $tuples[$query->getId()] = $allTuples;
}

// load all answers
$answers = $FACTORIES::getTwoCompareAnswerFactory()->filter(array());
foreach ($answers as $answer) {
  $answerSession = $FACTORIES::getAnswerSessionFactory()->get($answer->getAnswerSessionId());
  foreach ($queries as $query) {
    if (isset($tuples[$query->getId()][$answer->getResultTupleId()])) {
      // this answer should be included in the query export
      /** @var $tuple ResultTuple */
      $tuple = $tuples[$query->getId()][$answer->getResultTupleId()];
      if ($first[$query->getId()]) {
        // we need to add the header
        fputs($export[$query->getId()], "Query Object: ", $mediaObjectHashes[$tuple->getObjectId1()] . "\n");
        fputs($export[$query->getId()], "MediaObject,User,Answer\n");
        $first[$query->getId()] = false;
      }
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
      fputs($export[$query->getId()], $tuple->getObjectId2() . "," . $id . "," . $answer->getAnswer() . "\n");
    }
  }
}

foreach ($export as $exp) {
  fclose($exp);
}

















