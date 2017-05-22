<?php

/** @var $OBJECTS array */
use DBA\Game;
use DBA\OrderFilter;
use DBA\QueryFilter;

/** @var $OAUTH OAuthLogin */

require_once(dirname(__FILE__) . "/inc/load.php");

if (!$OAUTH->isLoggedin() && !isset($_GET['id'])) {
  header("Location: index.php?err=4" . time());
  die();
}

$MENU->setActive("profile");
$OBJECTS['pageTitle'] = "Profile";
$TEMPLATE = new Template("content/profile");

$player = null;
if ($OAUTH->isLoggedin() && !isset($_GET['id'])) {
  $player = $OAUTH->getPlayer();
}
else {
  $player = $FACTORIES::getPlayerFactory()->get($_GET['id']);
  if ($player == null) {
    UI::printError("ERROR", "Invalid User!");
  }
}
$OBJECTS['player'] = $player;

$qF = new QueryFilter(Game::PLAYER_ID, $player->getId(), "=");
$oF = new OrderFilter(Game::FINISHED_TIME, "ASC");
$games = $FACTORIES::getGameFactory()->filter(array($FACTORIES::FILTER => $qF, $FACTORIES::ORDER => $oF));
$totalScore = 0;
$playedGames = 0;
/** @var $latestGame Game */
$latestGame = null;
/** @var $highestBaseGame Game */
$highestBaseGame = null;
/** @var $highestFullGame Game */
$highestFullGame = null;
foreach ($games as $game) {
  if ($highestBaseGame == null || $highestBaseGame->getGameScore() < $game->getGameScore()) {
    $highestBaseGame = $game;
  }
  if ($highestFullGame == null || $highestFullGame->getFullScore() < $game->getFullScore()) {
    $highestFullGame = $game;
  }
  $totalScore += $game->getFullScore();
  $playedGames++;
  $latestGame = $game;
}

if ($playedGames > 0) {
  $averageScore = Util::number(floor($totalScore / $playedGames));
  $highestBase = Util::number($highestBaseGame->getGameScore());
  $highestFull = Util::number($highestFullGame->getFullScore());
  $oF1 = new OrderFilter(Game::GAME_SCORE, "DESC");
  $oF2 = new OrderFilter(Game::FINISHED_TIME, "DESC");
  $games = $FACTORIES::getGameFactory()->filter(array($FACTORIES::ORDER => array($oF1, $oF2)));
  $baseRank = 0;
  $count = 1;
  foreach ($games as $game) {
    if ($game->getId() == $highestBaseGame->getId()) {
      $baseRank = $count;
    }
    $count++;
  }
  $oF1 = new OrderFilter(Game::FULL_SCORE, "DESC");
  $oF2 = new OrderFilter(Game::FINISHED_TIME, "DESC");
  $games = $FACTORIES::getGameFactory()->filter(array($FACTORIES::ORDER => array($oF1, $oF2)));
  $fullRank = 0;
  $count = 1;
  foreach ($games as $game) {
    if ($game->getId() == $highestFullGame->getId()) {
      $fullRank = $count;
    }
    $count++;
  }
}
else {
  $averageScore = "N/A";
  $baseRank = "--";
  $fullRank = "--";
  $highestFull = "N/A";
  $highestBase = "N/A";
}
$totalScore = Util::number($totalScore);

$OBJECTS['highestBase'] = $highestBase;
$OBJECTS['highestFull'] = $highestFull;
$OBJECTS['playedGames'] = $playedGames;
$OBJECTS['averageScore'] = $averageScore;
$OBJECTS['baseRank'] = $baseRank;
$OBJECTS['fullRank'] = $fullRank;
$OBJECTS['latestGame'] = $latestGame;
$OBJECTS['totalScore'] = $totalScore;

echo $TEMPLATE->render($OBJECTS);







