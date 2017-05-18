<?php

/** @var $OBJECTS array */

require_once(dirname(__FILE__) . "/inc/load.php");
if (!$LOGIN->isLoggedin()) {
  header("Location: admin.php?err=4" . time());
  die();
}
$TEMPLATE = new Template("content/microworkers/index");

$MENU->setActive("microworkers");
$OBJECTS['pageTitle'] = "Cineast Evaluator";
$OBJECTS['administrator'] = true;

if (isset($_POST['action'])) {
  //$mediaTypeHandler = new MediaTypeHandler();
  //$mediaTypeHandler->handle($_POST['action']);
}



echo $TEMPLATE->render($OBJECTS);