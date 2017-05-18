<?php

require_once(dirname(__FILE__) . "/inc/load.php");

$MTURK = new MTurk();
$OBJECTS['pageTitle'] = "Finished";

if (!$MTURK->isMechanicalTurk()) {
  // continue with current session or close session if it's finished
  header("Location: index.php");
  die();
}

$USER_SESSION = new UserSession();
if ($USER_SESSION->getRemainingQuestions() > 0) {
  // there are still remaining questions
  header("Location: session.php");
  die();
}

// close session
$USER_SESSION->close();
$microworker = $MTURK->getMicroworker();

if (strlen($microworker->getSurveyCode()) == 0) {
  // we need to generate a new confirmation code
  $confirmCode = Util::randomString(20);
  $microworker->setSurveyCode($confirmCode);
  $FACTORIES::getMicroworkerFactory()->update($microworker);
}

$OBJECTS['surveyCode'] = $microworker->getSurveyCode();

$TEMPLATE = new Template("content/micro_finish");
echo $TEMPLATE->render($OBJECTS);