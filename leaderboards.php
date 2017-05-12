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

$oF1 = new OrderFilter(Game::GAME_SCORE, "DESC");
$oF2 = new OrderFilter(Game::GAME_ID, "DESC LIMIT 10");
$OBJECTS['baseGames'] = $FACTORIES::getGameFactory()->filter(array($FACTORIES::ORDER => array($oF1, $oF2)));

$players = $FACTORIES::getPlayerFactory()->filter(array());
$scores = array();
foreach ($players as $player) {
  $qF = new QueryFilter(Game::PLAYER_ID, $player->getId(), "=");
  $sum = 0;
  $games = $FACTORIES::getGameFactory()->filter(array($FACTORIES::FILTER => $qF));
  foreach ($games as $game) {
    $sum += $game->getFullScore();
  }
  $inserted = false;
  for ($i = 0; $i < sizeof($scores); $i++) {
    if ($scores[$i]->getVal('score') < $sum) {
      $size = sizeof($scores);
      for ($j = $size; $j > $i; $j--) {
        $scores[$j] = $scores[$j - 1];
      }
      $scores[$i] = new DataSet(array('score' => $sum, 'playerId' => $player->getId()));
      $inserted = true;
      break;
    }
  }
  if (!$inserted) {
    $scores[] = new DataSet(array('score' => $sum, 'playerId' => $player->getId()));
  }
}

$OBJECTS['totalScore'] = $scores;

echo $TEMPLATE->render($OBJECTS);