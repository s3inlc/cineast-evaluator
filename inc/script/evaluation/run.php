<?php
/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 26.06.17
 * Time: 09:56
 */

use DBA\AnswerSession;
use DBA\Game;
use DBA\JoinFilter;
use DBA\Microworker;
use DBA\QueryFilter;
use DBA\ResultTuple;
use DBA\TwoCompareAnswer;

require_once(dirname(__FILE__) . "/../../load.php");

$FACTORIES::getAnswerSessionFactory()->startTransation();

// determine some global numbers
$SINGLE = array();

$SINGLE['totalQueries'] = $FACTORIES::getQueryFactory()->countFilter(array());
$SINGLE['totalTuples'] = $FACTORIES::getResultTupleFactory()->countFilter(array());

$qF = new QueryFilter(ResultTuple::IS_FINAL, 1, "=");
$SINGLE['fullyEvaluatedTuples'] = $FACTORIES::getResultTupleFactory()->countFilter(array($FACTORIES::FILTER => $qF));

$SINGLE['totalPlayers'] = $FACTORIES::getPlayerFactory()->countFilter(array());

$qF = new QueryFilter(Microworker::IS_CONFIRMED, 1, "=");
$SINGLE['totalMicroworkers'] = $FACTORIES::getMicroworkerFactory()->countFilter(array($FACTORIES::FILTER => $qF));

$SINGLE['totalGames'] = $FACTORIES::getGameFactory()->countFilter(array());
$SINGLE['totalSessions'] = $FACTORIES::getAnswerSessionFactory()->countFilter(array());
$SINGLE['totalAnswers'] = $FACTORIES::getTwoCompareAnswerFactory()->countFilter(array());

$qF = new QueryFilter(AnswerSession::MICROWORKER_ID, null, "<>", $FACTORIES::getAnswerSessionFactory());
$SINGLE['microworkerSessions'] = $FACTORIES::getAnswerSessionFactory()->countFilter(array($FACTORIES::FILTER => $qF));
$jF = new JoinFilter($FACTORIES::getAnswerSessionFactory(), AnswerSession::ANSWER_SESSION_ID, TwoCompareAnswer::ANSWER_SESSION_ID);
$SINGLE['microworkerAnswers'] = sizeof($FACTORIES::getTwoCompareAnswerFactory()->filter(array($FACTORIES::FILTER => $qF, $FACTORIES::JOIN => $jF))[$FACTORIES::getTwoCompareAnswerFactory()->getModelName()]);

$qF1 = new QueryFilter(AnswerSession::MICROWORKER_ID, null, "=", $FACTORIES::getAnswerSessionFactory());
$qF2 = new QueryFilter(AnswerSession::USER_ID, null, "=", $FACTORIES::getAnswerSessionFactory());
$SINGLE['playerSessions'] = $FACTORIES::getAnswerSessionFactory()->countFilter(array($FACTORIES::FILTER => array($qF1, $qF2)));
$jF = new JoinFilter($FACTORIES::getAnswerSessionFactory(), AnswerSession::ANSWER_SESSION_ID, TwoCompareAnswer::ANSWER_SESSION_ID);
$SINGLE['playerAnswers'] = sizeof($FACTORIES::getTwoCompareAnswerFactory()->filter(array($FACTORIES::FILTER => array($qF2, $qF1), $FACTORIES::JOIN => $jF))[$FACTORIES::getTwoCompareAnswerFactory()->getModelName()]);

// find some fully evaluated tuples with narrow and wide curve
$results = $FACTORIES::getAnswerSessionFactory()->getDB()->query("SELECT ResultTuple.* FROM ResultTuple WHERE isFinal=1 AND (SELECT count(*) FROM TwoCompareAnswer WHERE TwoCompareAnswer.resultTupleId=ResultTuple.resultTupleId) >= 10 ORDER BY sigma DESC LIMIT 10");
foreach($results as $result){
  echo "Wide: ".$result['resultTupleId']."\n";
}
$results = $FACTORIES::getAnswerSessionFactory()->getDB()->query("SELECT ResultTuple.* FROM ResultTuple WHERE isFinal=1 AND (SELECT count(*) FROM TwoCompareAnswer WHERE TwoCompareAnswer.resultTupleId=ResultTuple.resultTupleId) >= 3 ORDER BY sigma ASC LIMIT 10");
foreach($results as $result){
  echo "Narrow: ".$result['resultTupleId']."\n";
}


// save all the global values
$lines = array();
foreach ($SINGLE as $key => $value) {
  $lines[] = $key . ":" . $value;
}
file_put_contents(dirname(__FILE__) . "/output/global.txt", implode("\n", $lines));

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
  $gamesPerDayOverall[] = array("playerId" => $player->getId(), "games" => round(sizeof($games) / floor(($range[1] - $range[0]) / 3600 / 24 + 1), 2));
}
saveCSV($gamesPlayed, dirname(__FILE__) . "/output/gamesPlayed.csv");
saveCSV($daysOfPlaying, dirname(__FILE__) . "/output/daysOfPlaying.csv");
saveCSV($gamesPerDay, dirname(__FILE__) . "/output/gamesPerDay.csv");
saveCSV($gamesPerDayOverall, dirname(__FILE__) . "/output/gamesPerDayOverall.csv");

// get the session validities for users
$answerSessions = $FACTORIES::getAnswerSessionFactory()->filter(array());
$allAnswerSessions = array();
$playerAnswerSessions = array();
$microworkerAnswerSessions = array();
$unknownAnswerSessions = array();
foreach ($answerSessions as $answerSession) {
  if ($answerSession->getUserId() != null) {
    // skip admin sessions
    continue;
  }
  $allAnswerSessions[] = array("answerSessionId" => $answerSession->getId(), "validity" => $answerSession->getCurrentValidity());
  if ($answerSession->getMicroworkerId() != null) {
    $microworkerAnswerSessions[] = array("answerSessionId" => $answerSession->getId(), "validity" => $answerSession->getCurrentValidity());
  }
  else if ($answerSession->getPlayerId() != null) {
    $playerAnswerSessions[] = array("answerSessionId" => $answerSession->getId(), "validity" => $answerSession->getCurrentValidity());
  }
  else {
    $unknownAnswerSessions[] = array("answerSessionId" => $answerSession->getId(), "validity" => $answerSession->getCurrentValidity());
  }
}
saveCSV($allAnswerSessions, dirname(__FILE__) . "/output/allValidities.csv");
saveCSV($microworkerAnswerSessions, dirname(__FILE__) . "/output/microworkerValidities.csv");
saveCSV($playerAnswerSessions, dirname(__FILE__) . "/output/playerValidities.csv");
saveCSV($unknownAnswerSessions, dirname(__FILE__) . "/output/anonymousValidities.csv");


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