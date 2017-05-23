<?php

/** @var $OBJECTS array */

require_once(dirname(__FILE__) . "/inc/load.php");
if (!$LOGIN->isLoggedin()) {
  header("Location: admin.php?err=4" . time());
  die();
}
$TEMPLATE = new Template("views/preview");

$OBJECTS['pageTitle'] = "Cineast Evaluator";
$OBJECTS['administrator'] = true;

$mediaObject = $FACTORIES::getMediaObjectFactory()->get($_GET['object']);
if ($mediaObject == null) {
  UI::addErrorMessage("Invalid media object!");
}
else {
  $mediaType = $FACTORIES::getMediaTypeFactory()->get($mediaObject->getMediaTypeId());
  $OBJECTS['template'] = $mediaType->getTemplate();
  $OBJECTS['object'] = new DataSet(array("data" => "serve.php?id=" . $mediaObject->getChecksum(), "source" => $mediaObject->getSource()));
}

echo $TEMPLATE->render($OBJECTS);