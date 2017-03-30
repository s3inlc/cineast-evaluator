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
  // TODO: handle submitted response
  // TODO: update session validity
}

$question = $USER_SESSION->getNextQuestion();
if ($question == null) {
  die("Something strange happened! We have no more questions for you...");
}

$value1 = new DataSet();
$value2 = new DataSet();

$value1->addValue('objData', array("serve.php?id=" . $question->getMediaObjects()[0]->getChecksum()));
$value2->addValue('objData', array("serve.php?id=" . $question->getMediaObjects()[1]->getChecksum()));

$mediaType1 = $FACTORIES::getMediaTypeFactory()->get($question->getMediaObjects()[0]->getMediaTypeId());
$mediaType2 = $FACTORIES::getMediaTypeFactory()->get($question->getMediaObjects()[1]->getMediaTypeId());

$value1->addValue('template', $mediaType1->getTemplate());
$value2->addValue('template', $mediaType2->getTemplate());

$OBJECTS['object1'] = $question->getMediaObjects()[0];
$OBJECTS['object2'] = $question->getMediaObjects()[1];
$OBJECTS['value1'] = $value1;
$OBJECTS['value2'] = $value2;

$TEMPLATE = new Template("views/" . $question->getQuestionType());
echo $TEMPLATE->render($OBJECTS);