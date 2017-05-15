<?php

/** @var $OBJECTS array */

require_once(dirname(__FILE__) . "/inc/load.php");
$TEMPLATE = new Template("content/login");

if($OAUTH->isLoggedin()){
  header("Location: index.php");
  die();
}

$MENU->setActive("login");
$OBJECTS['pageTitle'] = GAME_NAME;

echo $TEMPLATE->render($OBJECTS);