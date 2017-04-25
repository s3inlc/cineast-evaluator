<?php

/** @var $OBJECTS array */

use DBA\Game;
use DBA\QueryFilter;

require_once(dirname(__FILE__) . "/inc/load.php");

$MENU->setActive("achievements");
$OBJECTS['pageTitle'] = "Achievements";
$TEMPLATE = new Template("content/achievements");

echo $TEMPLATE->render($OBJECTS);