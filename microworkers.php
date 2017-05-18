<?php

/** @var $OBJECTS array */

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
else {
  $TEMPLATE = new Template("content/microworkers/index");
  $batches = $FACTORIES::getMicroworkerBatchFactory()->filter(array());
  $OBJECTS['batches'] = $batches;
}

echo $TEMPLATE->render($OBJECTS);