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

echo "answerId,answer\n";
foreach ($answers as $answer) {
  echo $answer->getId() . "," . $answer->getAnswer() . "\n";
}