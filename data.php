<?php

/** @var $OBJECTS array */

require_once(dirname(__FILE__) . "/inc/load.php");
$TEMPLATE = new Template("content/data");

$MENU->setActive("data");
$OBJECTS['pageTitle'] = GAME_NAME;

if(isset($_GET['get'])){
  switch($_GET['get']){
    case 'full':
      header("Location: nightlyFull.7z");
      break;
    case 'minimal':
      header("Location: minimalFull.7z");
      break;
  }
}

$OBJECTS['latestBuild'] = filectime("nightlyFull.7z");

echo $TEMPLATE->render($OBJECTS);