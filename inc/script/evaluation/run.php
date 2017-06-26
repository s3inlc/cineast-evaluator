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
foreach ($players as $player) {
  $qF = new QueryFilter(Game::PLAYER_ID, $player->getId(), "=");
  $games = $FACTORIES::getGameFactory()->filter(array($FACTORIES::FILTER => $qF));
  $gamesPlayed[] = array("playerId" => $player->getId(), "gamesPlayed" => sizeof($games));
}
saveCSV($gamesPlayed, dirname(__FILE__) . "/output/gamesPlayed.csv");


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