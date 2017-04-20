<?php

require_once(dirname(__FILE__) . "/inc/load.php");
$OBJECTS['pageTitle'] = "Cineast Evaluator";

$USER_SESSION = new UserSession();

if (isset($_POST['answer'])) {
  $USER_SESSION->answerQuestion();
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

$TEMPLATE = new Template("views/" . $question->getQuestionType());
echo $TEMPLATE->render($OBJECTS);