<?php
/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 04.07.17
 * Time: 11:52
 */

use DBA\QueryFilter;
use DBA\TwoCompareAnswer;

require_once(dirname(__FILE__) . "/../../load.php");

$resultTuple = $FACTORIES::getResultTupleFactory()->get($argv[1]);
if ($resultTuple == null) {
  die("Invalid tuple!\n");
}

$qF = new QueryFilter(TwoCompareAnswer::RESULT_TUPLE_ID, $resultTuple->getId(), "=");
$answers = $FACTORIES::getTwoCompareAnswerFactory()->filter(array($FACTORIES::FILTER => $qF));

$ans = array();

echo "answer,count\n";
foreach ($answers as $answer) {
  if (!isset($ans[$answer->getAnswer()])) {
    $ans[$answer->getAnswer()] = 1;
  }
  else {
    $ans[$answer->getAnswer()]++;
  }
}

$text = array("Not Similar", "Slightly Similar", "Very Similar", "Nearly Identical");
for ($i = 0; $i < 4; $i++) {
  echo $text[$i] . "," . $ans[$i] . "\n";
}