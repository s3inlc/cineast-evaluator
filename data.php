<?php

/** @var $OBJECTS array */

require_once(dirname(__FILE__) . "/inc/load.php");
$TEMPLATE = new Template("content/data");

$MENU->setActive("data");
$OBJECTS['pageTitle'] = GAME_NAME;

echo $TEMPLATE->render($OBJECTS);