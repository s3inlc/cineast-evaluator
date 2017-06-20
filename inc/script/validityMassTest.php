<?php
/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 19.05.17
 * Time: 14:18
 */

use DBA\AnswerSession;
use DBA\ContainFilter;
use DBA\TwoCompareAnswer;

require_once(dirname(__FILE__) . "/../load.php");

$numTests = 1;
$answerSessions = array();
$pool = new QuestionPool();
for ($i = 0; $i < $numTests; $i++) {
  $answerSession = new AnswerSession(0, null, null, null, 0, 0, time(), "999.999.999.999", "EMPTY");
  $answerSession = $FACTORIES::getAnswerSessionFactory()->save($answerSession);
  $answerSessions[] = $answerSession->getId();
  
  $questions = $pool->getNextQuestionBlock($answerSession);
  $answers = array();
  foreach ($questions as $question) {
    $answer = mt_rand(0, 3); // get a random answer
    $twoCompareAnswer = new TwoCompareAnswer(0, time(), $question->getResultTuples()[0]->getId(), $answer, $answerSession->getId());
    $answers[] = $twoCompareAnswer;
  }
  $FACTORIES::getTwoCompareAnswerFactory()->massSave($answers);
  
  // get the validity
  $validator = new MultivariantCrowdValidator();
  $currentValidity = $validator->validateFinished($session, 0, false);
  $validator = new PatternValidator();
  $currentValidity = $validator->validateFinished($session, $currentValidity);
  $validator = new TimeValidator();
  $currentValidity = $validator->validateFinished($session, $currentValidity);
  echo "Validity: " . $currentValidity . "\n";
}

// cleanup
$qF = new ContainFilter(TwoCompareAnswer::ANSWER_SESSION_ID, $answerSessions);
$FACTORIES::getTwoCompareAnswerFactory()->massDeletion(array($FACTORIES::FILTER => $qF));

$qF = new ContainFilter(AnswerSession::ANSWER_SESSION_ID, $answerSessions);
$FACTORIES::getAnswerSessionFactory()->massDeletion(array($FACTORIES::FILTER => $qF));

