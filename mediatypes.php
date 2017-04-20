<?php

/** @var $OBJECTS array */

require_once(dirname(__FILE__) . "/inc/load.php");
if (!$LOGIN->isLoggedin()) {
  header("Location: admin.php?err=4" . time());
  die();
}
$TEMPLATE = new Template("content/mediatypes/index");

$MENU->setActive("mediatypes");
$OBJECTS['pageTitle'] = "Cineast Evaluator";
$OBJECTS['administrator'] = true;

if (isset($_POST['action'])) {
  $mediaTypeHandler = new MediaTypeHandler();
  $mediaTypeHandler->handle($_POST['action']);
}

if (isset($_GET['edit'])) {
  $mediaType = $FACTORIES::getMediaTypeFactory()->get($_GET['edit']);
  if ($mediaType != null) {
    $TEMPLATE = new Template("content/mediatypes/edit");
    $OBJECTS['mediaType'] = $mediaType;
    
    $dir = TEMPLATES_PATH . "/media/";
    $templates = array();
    foreach (scandir($dir) as $entry) {
      if (strpos($entry, ".template.html") !== false) {
        $templates[] = str_replace(".template.html", "", $entry);
      }
    }
    $OBJECTS['templates'] = $templates;
  }
}

$mediaTypes = $FACTORIES::getMediaTypeFactory()->filter(array());
$OBJECTS['mediaTypes'] = $mediaTypes;

echo $TEMPLATE->render($OBJECTS);