<?php

/** @var $OBJECTS array */

use DBA\Microworker;
use DBA\QueryFilter;

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
else {
  $TEMPLATE = new Template("content/microworkers/index");
  $batches = $FACTORIES::getMicroworkerBatchFactory()->filter(array());
  $OBJECTS['batches'] = $batches;
}

echo $TEMPLATE->render($OBJECTS);