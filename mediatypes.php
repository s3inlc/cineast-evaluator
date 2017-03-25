<?php
/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 22.03.17
 * Time: 17:35
 */

/** @var $OBJECTS array */

require_once(dirname(__FILE__) . "/inc/load.php");
if (!$LOGIN->isLoggedin()) {
  header("Location: index.php?err=4" . time());
  die();
}
$TEMPLATE = new Template("content/mediatypes");

$MENU->setActive("mediatypes");
$OBJECTS['pageTitle'] = "Cineast Evaluator";

if(isset($_POST['action'])){
  $mediaTypeHandler = new MediaTypeHandler();
  $mediaTypeHandler->handle($_POST['action']);
}


$mediaTypes = $FACTORIES::getMediaTypeFactory()->filter(array());
$OBJECTS['mediaTypes'] = $mediaTypes;

echo $TEMPLATE->render($OBJECTS);