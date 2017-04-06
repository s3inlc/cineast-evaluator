<?php
use DBA\AnswerSession;
use DBA\JoinFilter;
use DBA\QueryFilter;
use DBA\ResultTuple;
use DBA\TwoCompareAnswer;

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 27.03.17
 * Time: 18:23
 */
class SimilaritySumCalculator extends Calculator {
  /**
   * @param $resultSets ResultTuple[]
   */
  function updateSimilarities(&$resultSets, &$changed) {
    global $FACTORIES;
    
    foreach ($resultSets as $resultSet) {
      $qF = new QueryFilter(TwoCompareAnswer::RESULT_TUPLE_ID, $resultSet->getId(), "=");
      $jF = new JoinFilter($FACTORIES::getAnswerSessionFactory(), TwoCompareAnswer::ANSWER_SESSION_ID, AnswerSession::ANSWER_SESSION_ID);
      $joined = $FACTORIES::getTwoCompareAnswerFactory()->filter(array($FACTORIES::FILTER => $qF, $FACTORIES::JOIN => $jF));
      $qualitySum = array(0, 0, 0, 0);
      for ($i = 0; $i < sizeof($joined['TwoCompareAnswer']); $i++) {
        /** @var $twoCompareAnswer TwoCompareAnswer */
        $twoCompareAnswer = $joined['TwoCompareAnswer'][$i];
        /** @var $answerSession AnswerSession */
        $answerSession = $joined['AnswerSession'][$i];
        $qualitySum[$twoCompareAnswer->getAnswer()] += $answerSession->getCurrentValidity();
      }
      $qualityNormalized = array(0, 0, 0, 0);
      $totalSum = 0;
      $idealValue = 0;
      for ($i = 0; $i < 4; $i++) {
        $totalSum += $qualitySum[$i];
        $idealValue += $qualitySum[$i] * $i;
      }
      if ($totalSum == 0) {
        continue;
      }
      $idealValue /= $totalSum;
      if($idealValue > 3){
        $idealValue = 3;
      }
      for ($i = 0; $i < 4; $i++) {
        $qualityNormalized[$i] = $qualitySum[$i] / $totalSum * (1 - 1 / ($totalSum + 1));
      }
      if (floor($idealValue) == $idealValue) { // if it really matches one value
        $quality = $qualityNormalized[$idealValue];
      }
      else if(floor($idealValue) == 3){
        $quality = $qualityNormalized[$idealValue];
      }
      else { // otherwise just interpolate linearly between the two nearest values
        $lower = intval(floor($idealValue));
        $upper = $lower + 1;
        $m = $qualityNormalized[$upper] - $qualityNormalized[$lower];
        $b = $qualityNormalized[$lower] - $m * $lower;
        $quality = $m * $idealValue + $b;
      }
      
      if (sizeof($joined['TwoCompareAnswer']) > 0) {
        $changed[$resultSet->getId()] = true;
        $resultSet->setSimilarity($idealValue);
        $resultSet->setCertainty($quality);
      }
    }
  }
}