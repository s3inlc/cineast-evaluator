<?php
use DBA\QueryFilter;
use DBA\ResultTuple;
use DBA\TwoCompareAnswer;

class SimilarityGaussCalculator extends Calculator {
  
  /**
   * @param $resultSets ResultTuple[]
   * @param $changed bool[]
   */
  function updateSimilarities(&$resultSets, &$changed) {
    global $FACTORIES;
    
    foreach ($resultSets as $resultSet) {
      if ($resultSet->getIsFinal() == 1) {
        continue; // we ignore the sets marked as final
      }
      
      if ($resultSet->getObjectId2() == $resultSet->getObjectId1()) {
        $resultSet->setIsFinal(1);
        $resultSet->setSigma(0);
        $resultSet->setMu(AnswerType::COMPARE_TWO_NEARLY_IDENTICAL);
        $changed[$resultSet->getId()] = true;
        continue;
      }
      
      $gauss = new SimpleGauss($resultSet);
      if ($gauss->getSigma() != $resultSet->getSigma() || $gauss->getMu() != $resultSet->getMu()) {
        $resultSet->setSigma($gauss->getSigma());
        $resultSet->setMu($gauss->getMu());
        $changed[$resultSet->getId()] = true;
      }
      if ($resultSet->getSigma() <= RESULT_TUPLE_EVALUATED_SIGMA_THRESHOLD && $resultSet->getSigma() > 0) {
        $qF = new QueryFilter(TwoCompareAnswer::RESULT_TUPLE_ID, $resultSet->getId(), "=");
        $count = $FACTORIES::getTwoCompareAnswerFactory()->countFilter(array($FACTORIES::FILTER => $qF));
        if ($count >= RESULT_TUPLE_EVALUATED_ANSWERS_THRESHOLD) {
          $resultSet->setIsFinal(1);
          $changed[$resultSet->getId()] = true;
        }
      }
    }
  }
}