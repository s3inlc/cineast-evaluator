<?php

/** @var $OBJECTS array */

use DBA\Game;
use DBA\QueryFilter;

require_once(dirname(__FILE__) . "/inc/load.php");

$MENU->setActive("settings");
$OBJECTS['pageTitle'] = "Settings";
$TEMPLATE = new Template("content/settings");

echo $TEMPLATE->render($OBJECTS);