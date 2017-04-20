<?php

/** @var $OBJECTS array */

require_once(dirname(__FILE__) . "/inc/load.php");
$TEMPLATE = new Template("content/home");

$MENU->setActive("home");
$OBJECTS['pageTitle'] = "TODO: good name";

echo $TEMPLATE->render($OBJECTS);