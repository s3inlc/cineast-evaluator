<?php
/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 22.03.17
 * Time: 17:35
 */
use DBA\MediaType;
use DBA\QueryFilter;

/** @var $OBJECTS array */

require_once(dirname(__FILE__) . "/inc/load.php");
if (!$LOGIN->isLoggedin()) {
  header("Location: index.php?err=4" . time());
  die();
}
$TEMPLATE = new Template("views/preview");

$OBJECTS['pageTitle'] = "Cineast Evaluator";
$OBJECTS['administrator'] = true;

$mediaObject = $FACTORIES::getMediaObjectFactory()->get($_GET['object']);
if($mediaObject == null){
  UI::addErrorMessage("Invalid media object!");
}
else{
  $mediaType = $FACTORIES::getMediaTypeFactory()->get($mediaObject->getMediaTypeId());
  $OBJECTS['template'] = $mediaType->getTemplate();
  $OBJECTS['objectSource'] = "serve.php?id=".$mediaObject->getChecksum();
}

echo $TEMPLATE->render($OBJECTS);