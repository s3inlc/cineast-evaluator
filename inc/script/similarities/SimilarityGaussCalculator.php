<?php
use DBA\ResultTuple;

class SimilarityGaussCalculator extends Calculator {
  
  /**
   * @param $resultSets ResultTuple[]
   * @param $changed bool[]
   */
  function updateSimilarities(&$resultSets, &$changed) {
    foreach ($resultSets as $resultSet) {
      if ($resultSet->getIsFinal() == 1) {
        continue; // we ignore the sets marked as final
      }
      $gauss = new SimpleGauss($resultSet);
      if ($gauss->getSigma() != $resultSet->getSigma() || $gauss->getMu() != $resultSet->getMu()) {
        $resultSet->setSigma($gauss->getSigma());
        $resultSet->setMu($gauss->getMu());
        $changed[$resultSet->getId()] = true;
      }
    }
  }
}