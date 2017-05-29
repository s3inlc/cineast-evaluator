<?php

require_once(dirname(__FILE__) . "/inc/load.php");
$OBJECTS['pageTitle'] = GAME_NAME;

/**
 * @var $DEBUG string[]
 */

if (isset($_POST['disclaimerAccept'])) {
  // user accepted the disclaimer
  setcookie("disclaimer", "accepted", time() + 3600 * 24 * 30);
  header("Location: session.php");
  die();
}
else if (!isset($_COOKIE['disclaimer']) || $_COOKIE['disclaimer'] != 'accepted') {
  // user has not accepted disclaimer yet
  $TEMPLATE = new Template("content/disclaimer");
  echo $TEMPLATE->render($OBJECTS);
  die();
}

if (isset($_GET['refer']) && $_GET['refer'] == "game") {
  // user plays a game in a row
  if (time() - $_SESSION['lastCompleted'] < 100) {
    $_SESSION['lastCompleted'] = 0;
    // increase count
    if (!isset($_SESSION['gamesInRow'])) {
      $_SESSION['gamesInRow'] = 1;
    }
    else {
      $_SESSION['gamesInRow'] = $_SESSION['gamesInRow'] + 1;
    }
  }
  else {
    $_SESSION['gamesInRow'] = 0;
  }
  header("Location: session.php");
  die();
}

// show the help modal immediately when a user starts his first session
$OBJECTS['forceOpenHelp'] = false;
if (!isset($_COOKIE['help'])) {
  $OBJECTS['forceOpenHelp'] = true;
  setcookie("help", "done", time() + 3600 * 24 * 30);
}

$USER_SESSION = new UserSession();

if (isset($_POST['answer'])) {
  $USER_SESSION->answerQuestion();
}

// TODO: test here if the user is not authenticated and has finished a session now
if ($USER_SESSION->getAnswerSession()->getMicroworkerId() == null && $USER_SESSION->getAnswerSession()->getUserId() == null && $USER_SESSION->getRemainingQuestions() == 0) {
  $USER_SESSION->close();
  header("Location: score.php");
  die();
}
else if ($USER_SESSION->getAnswerSession()->getMicroworkerId() != null && $USER_SESSION->getRemainingQuestions() == 0) {
  // it's a microworker
  // we need to handle this special here and not start a new session
  header("Location: microworker_finished.php");
  die();
}

$question = $USER_SESSION->getNextQuestion();
if ($question == null) {
  die("Something strange happened! We have no more questions for you...");
}

// TODO: this needs to be updated later to also adapt for compare3 questions
Util::prepare2CompareQuestion($question->getMediaObjects()[0], $question->getMediaObjects()[1], $question->getResultTuples()[0]);

$sessionSize = SESSION_SIZE_GAME;
if ($USER_SESSION->getAnswerSession()->getMicroworkerId() != null) {
  $sessionSize = SESSION_SIZE_MICROWORKER;
}
$progress = floor(($sessionSize - $USER_SESSION->getRemainingQuestions() - 1) * 100 / $sessionSize);
$OBJECTS['progress'] = $progress;

if (ini_get("display_errors") == "1" || $USER_SESSION->getAnswerSession()->getPlayerId() == 3) {
  $tuple = $question->getResultTuples()[0];
  $debug = array(
    "AnswerSession ID: " . $_SESSION['answerSessionId'],
    "Current Validity: " . $USER_SESSION->getAnswerSession()->getCurrentValidity()
  );
  if ($tuple->getIsFinal()) {
    $debug[] = "This is a security question!";
  }
  if ($tuple->getMu() != -1 && $tuple->getSigma() != -1) {
    if ($tuple->getSigma() == 0) {
      $tuple->setSigma(0.01);
    }
    $debug[] = "Current Gaussian: <br><img class='img-responsive' src='" . SimpleGauss::generateCurve($tuple->getSigma(), $tuple->getMu()) . "' alt='gaussian'>";
  }
  $OBJECTS['debug'] = $debug;
  $OBJECTS['DEBUG'] = $DEBUG;
}

if ($USER_SESSION->getAnswerSession()->getMicroworkerId() == null) {
  $OBJECTS['showMenu'] = true;
}

$TEMPLATE = new Template("views/" . $question->getQuestionType());
echo $TEMPLATE->render($OBJECTS);