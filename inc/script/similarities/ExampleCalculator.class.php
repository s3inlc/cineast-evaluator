<?php
use DBA\AnswerSession;
use DBA\JoinFilter;
use DBA\QueryFilter;
use DBA\ResultTuple;
use DBA\TwoCompareAnswer;

// TODO: here do some really basic similarity stuff

class ExampleCalculator extends Calculator {
  /**
   * @param $resultSets ResultTuple[]
   */
  function updateSimilarities(&$resultSets, &$changed) {
    global $FACTORIES;
    
    foreach ($resultSets as $resultSet) {
      $qF = new QueryFilter(TwoCompareAnswer::RESULT_TUPLE_ID, $resultSet->getId(), "=");
      $jF = new JoinFilter($FACTORIES::getAnswerSessionFactory(), TwoCompareAnswer::ANSWER_SESSION_ID, AnswerSession::ANSWER_SESSION_ID);
      $joined = $FACTORIES::getTwoCompareAnswerFactory()->filter(array($FACTORIES::FILTER => $qF, $FACTORIES::JOIN => $jF));
      $certaintySum = 0;
      $answerSum = 0;
      for ($i = 0; $i < sizeof($joined['TwoCompareAnswer']); $i++) {
        /** @var $twoCompareAnswer TwoCompareAnswer */
        $twoCompareAnswer = $joined['TwoCompareAnswer'][$i];
        /** @var $answerSession AnswerSession */
        $answerSession = $joined['AnswerSession'][$i];
        $certaintySum += $answerSession->getCurrentValidity();
        $answerSum += $twoCompareAnswer->getAnswer() * $answerSession->getCurrentValidity();
      }
      if (sizeof($joined['TwoCompareAnswer']) > 0 && $certaintySum > 0) {
        $changed[$resultSet->getId()] = true;
        $resultSet->setSigma($answerSum / $certaintySum);
      }
    }
  }
}