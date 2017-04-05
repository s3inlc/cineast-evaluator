<?php
/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 29.03.17
 * Time: 16:15
 */

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
$value1 = new DataSet();
$value2 = new DataSet();

$pos = random_int(0, 1);
$mediaObject1 = $question->getMediaObjects()[$pos];
$mediaObject2 = $question->getMediaObjects()[($pos+1)%2];

$value1->addValue('objData', array("serve.php?id=" . $mediaObject1->getChecksum()));
$value2->addValue('objData', array("serve.php?id=" . $mediaObject2->getChecksum()));

$mediaType1 = $FACTORIES::getMediaTypeFactory()->get($mediaObject1->getMediaTypeId());
$mediaType2 = $FACTORIES::getMediaTypeFactory()->get($mediaObject2->getMediaTypeId());

$value1->addValue('template', $mediaType1->getTemplate());
$value2->addValue('template', $mediaType2->getTemplate());

$OBJECTS['object1'] = $mediaObject1;
$OBJECTS['object2'] = $mediaObject2;
$OBJECTS['value1'] = $value1;
$OBJECTS['value2'] = $value2;
// TODO: until here

if(ini_get("display_errors") == "1"){
  $debug = array(
    "Number of security questions pushed: ".$_SESSION['numSecurityQuestions'],
    "Number of questions in queue: ".sizeof(unserialize($_SESSION['questions'])),
    "AnswerSession ID: ".$_SESSION['answerSessionId']
  );
  $OBJECTS['debug'] = $debug;
}

$TEMPLATE = new Template("views/" . $question->getQuestionType());
echo $TEMPLATE->render($OBJECTS);