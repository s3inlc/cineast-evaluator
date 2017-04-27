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

$oF1 = new OrderFilter(Game::GAME_SCORE, "DESC LIMIT 10");
$oF2 = new OrderFilter(Game::GAME_ID, "ASC");
$OBJECTS['baseGames'] = $FACTORIES::getGameFactory()->filter(array($FACTORIES::ORDER => array($oF1, $oF2)));

echo $TEMPLATE->render($OBJECTS);