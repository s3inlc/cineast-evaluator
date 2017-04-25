<?php

/** @var $OBJECTS array */

require_once(dirname(__FILE__) . "/inc/load.php");
$TEMPLATE = new Template("content/home");

$MENU->setActive("score");
$OBJECTS['pageTitle'] = "Game Score";
$TEMPLATE = new Template("content/score");

$answerSession = null;
$isFresh = false;
if (isset($_GET['game'])) {
  // we can show historical scores
}
else {
  $answerSession = $FACTORIES::getAnswerSessionFactory()->get($_SESSION['answerSessionId']);
  if ($answerSession->getIsOpen() != 0 || $answerSession->getUserId() != null || $answerSession->getMicroworkerId() != null || ($OAUTH->isLoggedin() && $answerSession->getPlayerId() != $OAUTH->getPlayer()->getId())) {
    $answerSession = null;
  }
  else{
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
$OBJECTS['score'] = new DataSet($scoreData);

if ($isFresh) {
  // TODO: test achievements and add it as info to page
}

echo $TEMPLATE->render($OBJECTS);