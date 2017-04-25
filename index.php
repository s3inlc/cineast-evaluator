<?php

/** @var $OBJECTS array */

require_once(dirname(__FILE__) . "/inc/load.php");
$TEMPLATE = new Template("content/home");

$MENU->setActive("home");
$OBJECTS['pageTitle'] = GAME_NAME;

echo $TEMPLATE->render($OBJECTS);