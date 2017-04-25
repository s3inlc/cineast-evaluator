<?php

/** @var $OBJECTS array */

use DBA\Game;
use DBA\QueryFilter;

require_once(dirname(__FILE__) . "/inc/load.php");

$MENU->setActive("leaderboards");
$OBJECTS['pageTitle'] = "Leaderboards";
$TEMPLATE = new Template("content/leaderboards");

echo $TEMPLATE->render($OBJECTS);