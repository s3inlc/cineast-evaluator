<?php

/** @var $OBJECTS array */

use DBA\AnswerSession;
use DBA\Microworker;
use DBA\QueryFilter;
use DBA\TwoCompareAnswer;

require_once(dirname(__FILE__) . "/inc/load.php");
if (!$LOGIN->isLoggedin()) {
  header("Location: admin.php?err=4" . time());
  die();
}

$MENU->setActive("microworkers");
$OBJECTS['pageTitle'] = "Cineast Evaluator";
$OBJECTS['administrator'] = true;

if (isset($_POST['action'])) {
  $microworkerBatchHandler = new MicroworkerBatchHandler();
  $microworkerBatchHandler->handle($_POST['action']);
}

if (isset($_GET['new'])) {
  $TEMPLATE = new Template("content/microworkers/new");
}
else if (isset($_GET['view'])) {
  $batch = $FACTORIES::getMicroworkerBatchFactory()->get($_GET['view']);
  if ($batch == null) {
    UI::printError("ERROR", "Invalid Batch!");
  }
  $TEMPLATE = new Template("content/microworkers/detail");
  $qF = new QueryFilter(Microworker::MICROWORKER_BATCH_ID, $batch->getId(), "=");
  $microworkers = $FACTORIES::getMicroworkerFactory()->filter(array($FACTORIES::FILTER => $qF));
  $OBJECTS['batch'] = $batch;
  $OBJECTS['microworkers'] = $microworkers;
}
else if(isset($_GET['microworkerId'])){
  $microworker = $FACTORIES::getMicroworkerFactory()->get($_GET['microworkerId']);
  if($microworker == null){
    UI::printError("ERROR", "Invalid Microworker!");
  }
  $TEMPLATE = new Template("content/microworkers/session");
  $qF = new QueryFilter(AnswerSession::MICROWORKER_ID, $microworker->getId(), "=");
  $answerSessions = $FACTORIES::getAnswerSessionFactory()->filter(array($FACTORIES::FILTER => $qF));
  $sessions = array();
  foreach($answerSessions as $answerSession){
    $set = new DataSet();
    $set->addValue('session', $answerSession);
    $qF = new QueryFilter(TwoCompareAnswer::ANSWER_SESSION_ID, $answerSession->getId(), "=");
    $answers = $FACTORIES::getTwoCompareAnswerFactory()->filter(array($FACTORIES::FILTER => $qF));
    $responses = array();
    foreach($answers as $answer){
      $answerSet = new DataSet();
      $tuple = $FACTORIES::getResultTupleFactory()->get($answer->getResultTupleId());
      $mediaObject1 = $FACTORIES::getMediaObjectFactory()->get($tuple->getObjectId1());
      $mediaObject2 = $FACTORIES::getMediaObjectFactory()->get($tuple->getObjectId2());

      $value1 = new DataSet();
      $value2 = new DataSet();
      $value1->addValue('objData', array(new DataSet(array("data" => "serve.php?id=" . $mediaObject1->getChecksum(), "source" => $mediaObject1->getSource()))));
      $value2->addValue('objData', array(new DataSet(array("data" => "serve.php?id=" . $mediaObject2->getChecksum(), "source" => $mediaObject2->getSource()))));

      $mediaType1 = $FACTORIES::getMediaTypeFactory()->get($mediaObject1->getMediaTypeId());
      $mediaType2 = $FACTORIES::getMediaTypeFactory()->get($mediaObject2->getMediaTypeId());
      $value1->addValue('template', $mediaType1->getTemplate());
      $value2->addValue('template', $mediaType2->getTemplate());

      $answerSet->addValue('object1', $mediaObject1);
      $answerSet->addValue('object2', $mediaObject2);
      $answerSet->addValue('value1', $value1);
      $answerSet->addValue('value1', $value2);
      $responses[] = $answerSet;
    }
    $set->addValue('answers', $responses);
    $sessions[] = $set;
  }
  $OBJECTS['sessions'] = $sessions;
}
else {
  $TEMPLATE = new Template("content/microworkers/index");
  $batches = $FACTORIES::getMicroworkerBatchFactory()->filter(array());
  $OBJECTS['batches'] = $batches;
}

echo $TEMPLATE->render($OBJECTS);