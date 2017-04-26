<?php

require_once(dirname(__FILE__) . "/inc/load.php");
$OBJECTS['pageTitle'] = "Cineast Evaluator";

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
}
else {
  $_SESSION['gamesInRow'] = 0;
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
else if ($USER_SESSION->getAnswerSession()->getMicroworkerId() != null) {
  // it's a microworker
  // we need to handle this special here and not start a new session
}

$question = $USER_SESSION->getNextQuestion();
if ($question == null) {
  die("Something strange happened! We have no more questions for you...");
}

// TODO: this needs to be updated later to also adapt for compare3 questions
Util::prepare2CompareQuestion($question->getMediaObjects()[0], $question->getMediaObjects()[1], $question->getResultTuples()[0]);

if (ini_get("display_errors") == "1") {
  $debug = array(
    "Number of security questions pushed: " . $_SESSION['numSecurityQuestions'],
    "Number of questions in queue: " . sizeof(unserialize($_SESSION['questions'])),
    "AnswerSession ID: " . $_SESSION['answerSessionId'],
    "Current Validity: " . $USER_SESSION->getAnswerSession()->getCurrentValidity()
  );
  $OBJECTS['debug'] = $debug;
}

if ($USER_SESSION->getAnswerSession()->getMicroworkerId() == null) {
  $OBJECTS['showMenu'] = true;
}

$TEMPLATE = new Template("views/" . $question->getQuestionType());
echo $TEMPLATE->render($OBJECTS);