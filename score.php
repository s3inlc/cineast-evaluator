<?php

/** @var $OBJECTS array */
/** @var $OAUTH OAuthLogin */

use DBA\Game;
use DBA\QueryFilter;

require_once(dirname(__FILE__) . "/inc/load.php");

$MENU->setActive("score");
$TEMPLATE = new Template("content/score");

$answerSession = null;
$isFresh = false;
if (isset($_GET['game'])) {
  // we can show historical scores
  $game = $FACTORIES::getGameFactory()->get($_GET['game']);
  if ($game != null) {
    $answerSession = $FACTORIES::getAnswerSessionFactory()->get($game->getAnswerSessionId());
  }
}
else {
  $answerSession = $FACTORIES::getAnswerSessionFactory()->get($_SESSION['answerSessionId']);
  if ($answerSession->getIsOpen() != 0 || $answerSession->getUserId() != null || $answerSession->getMicroworkerId() != null || ($OAUTH->isLoggedin() && $answerSession->getPlayerId() != $OAUTH->getPlayer()->getId())) {
    $answerSession = null;
  }
  else {
    $isFresh = true;
  }
}

if ($answerSession == null) {
  header("Location: index.php"); // TODO: maybe show some message here
  die();
}

// we show a score here
$scoreCalculator = new ScoreCalculator($answerSession);
$scoreData = $scoreCalculator->getScore();
$scoreData[ScoreCalculator::SCORE_MULTIPLICATOR] = round(($scoreData[ScoreCalculator::SCORE_MULTIPLICATOR] - 1) * 100, 2);
$OBJECTS['score'] = new DataSet($scoreData);

$OBJECTS['achievements'] = array();
if ($isFresh) {
  // test if game was saved for this answer session
  if ($OAUTH->isLoggedin()) {
    $qF = new QueryFilter(Game::ANSWER_SESSION_ID, $answerSession->getId(), "=");
    $game = $FACTORIES::getGameFactory()->filter(array($FACTORIES::FILTER => $qF), true);
    if ($game == null) {
      $_SESSION['lastCompleted'] = time();
      $game = new Game(0, $OAUTH->getPlayer()->getId(), $answerSession->getId(), time(), $scoreData[ScoreCalculator::SCORE_BASE], $scoreData[ScoreCalculator::SCORE_TOTAL]);
      $FACTORIES::getGameFactory()->save($game);
    }
  }
  
  // TODO: test achievements and add it as info to page
  $achievementTester = new AchievementTester();
  $OBJECTS['achievements'] = $achievementTester->getAchievements($OAUTH->getPlayer());
}

$qF = new QueryFilter(Game::ANSWER_SESSION_ID, $answerSession->getId(), "=");
$game = $FACTORIES::getGameFactory()->filter(array($FACTORIES::FILTER => $qF), true);
$OBJECTS['game'] = $game;
$OBJECTS['pageTitle'] = Util::number($scoreData[ScoreCalculator::SCORE_TOTAL]) . " points";
if ($game != null) {
  $OBJECTS['pageTitle'] .= " by " . Util::getPlayerNameById($game->getPlayerId());
}
$OBJECTS['isFresh'] = $isFresh;

echo $TEMPLATE->render($OBJECTS);