<?php
/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 19.05.17
 * Time: 14:18
 */

require_once(dirname(__FILE__) . "/../load.php");

$answerSession = $FACTORIES::getAnswerSessionFactory()->get($argv[1]);
$numFifty = 10;
$numHundred = 10;

$pool = new QuestionPool();
for ($i = 0; $i < $numFifty; $i++) {
  $start = microtime(true);
  $answerSession->setMicroworkerId(null);
  $answerSession->setUserId(3);
  $questions = $pool->getNextQuestionBlock($answerSession);
  echo "50 entries: " . (microtime(true) - $start) . " seconds\n";
}

for ($i = 0; $i < $numHundred; $i++) {
  $start = microtime(true);
  $answerSession->setMicroworkerId(1);
  $answerSession->setUserId(null);
  $questions = $pool->getNextQuestionBlock($answerSession);
  echo "100 entries: " . (microtime(true) - $start) . " seconds\n";
}




