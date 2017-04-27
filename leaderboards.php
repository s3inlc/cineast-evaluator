<?php

/** @var $OBJECTS array */

use DBA\Game;
use DBA\OrderFilter;
use DBA\QueryFilter;

require_once(dirname(__FILE__) . "/inc/load.php");

$MENU->setActive("leaderboards");
$OBJECTS['pageTitle'] = "Leaderboards";
$TEMPLATE = new Template("content/leaderboards");

// get 10 highest games
$oF = new OrderFilter(Game::FULL_SCORE, "DESC LIMIT 10");
$OBJECTS['games'] = $FACTORIES::getGameFactory()->filter(array($FACTORIES::ORDER => $oF));

$oF = new OrderFilter(Game::GAME_SCORE, "DESC LIMIT 10");
$OBJECTS['baseGames'] = $FACTORIES::getGameFactory()->filter(array($FACTORIES::ORDER => $oF));

echo $TEMPLATE->render($OBJECTS);