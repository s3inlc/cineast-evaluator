<?php
/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 26.06.17
 * Time: 09:56
 */

use DBA\Game;
use DBA\Microworker;
use DBA\QueryFilter;
use DBA\ResultTuple;

require_once(dirname(__FILE__) . "/../../load.php");

$FACTORIES::getAnswerSessionFactory()->startTransation();

// determine some global numbers

$totalQueries = $FACTORIES::getQueryFactory()->countFilter(array());
$totalTuples = $FACTORIES::getResultTupleFactory()->countFilter(array());

$qF = new QueryFilter(ResultTuple::IS_FINAL, 1, "=");
$fullyEvaluatedTuples = $FACTORIES::getResultTupleFactory()->countFilter(array($FACTORIES::FILTER => $qF));

$totalPlayers = $FACTORIES::getPlayerFactory()->countFilter(array());

$qF = new QueryFilter(Microworker::IS_CONFIRMED, 1, "=");
$totalMicroworkers = $FACTORIES::getMicroworkerFactory()->countFilter(array($FACTORIES::FILTER => $qF));

$totalGames = $FACTORIES::getGameFactory()->countFilter(array());


// days of playing by players and games per player
$players = $FACTORIES::getPlayerFactory()->filter(array());
$daysOfPlaying = array();
$gamesPlayed = array();
$gamesPerDay = array();
$gamesPerDayOverall = array();
foreach ($players as $player) {
  $qF = new QueryFilter(Game::PLAYER_ID, $player->getId(), "=");
  $games = $FACTORIES::getGameFactory()->filter(array($FACTORIES::FILTER => $qF));
  $gamesPlayed[] = array("playerId" => $player->getId(), "gamesPlayed" => sizeof($games));
  $days = array();
  $range = array(0, 0);
  foreach ($games as $game) {
    $date = date("d.m.Y", $game->getFinishedTime());
    // get range of days
    if ($range[0] > $game->getFinishedTime() || $range[0] == 0) {
      $range[0] = $game->getFinishedTime();
    }
    if ($range[1] < $game->getFinishedTime() || $range[1] == 0) {
      $range[1] = $game->getFinishedTime();
    }
    
    if (!isset($days[$date])) {
      $days[$date] = 1;
    }
    else {
      $days[$date]++;
    }
  }
  
  if (sizeof($games) == 0) {
    continue;
  }
  
  $daysOfPlaying[] = array("playerId" => $player->getId(), "days" => sizeof($days));
  $gamesPerDay[] = array("playerId" => $player->getId(), "games" => round(sizeof($games) / sizeof($days), 2));
  $gamesPerDayOverall[] = array("playerId" => $player->getId(), "games" => round(sizeof($games) / (($range[1] - $range[0]) / 3600 / 24 + 1), 2));
}
saveCSV($gamesPlayed, dirname(__FILE__) . "/output/gamesPlayed.csv");
saveCSV($daysOfPlaying, dirname(__FILE__) . "/output/daysOfPlaying.csv");
saveCSV($gamesPerDay, dirname(__FILE__) . "/output/gamesPerDay.csv");


/**
 * @param $elements array
 * @param $path
 */
function saveCSV($elements, $path) {
  if (sizeof($elements) == 0) {
    return;
  }
  $header = array();
  $arr = $elements[0];
  foreach ($arr as $key => $val) {
    $header[] = $key;
  }
  $output = implode(",", $header) . "\n";
  foreach ($elements as $element) {
    $output .= implode(",", $element) . "\n";
  }
  file_put_contents($path, $output);
}