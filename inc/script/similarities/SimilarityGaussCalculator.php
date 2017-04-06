<?php
use DBA\ResultTuple;

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 06.04.17
 * Time: 14:51
 */
class SimilarityGaussCalculator extends Calculator {
  
  /**
   * @param $resultSets ResultTuple[]
   * @param $changed bool[]
   */
  function updateSimilarities(&$resultSets, &$changed) {
    foreach ($resultSets as $resultSet) {
      $gauss = new SimpleGauss($resultSet);
      if ($gauss->getSigma() != $resultSet->getSigma() || $gauss->getMu() != $resultSet->getMu()) {
        $resultSet->setSigma($gauss->getSigma());
        $resultSet->setMu($gauss->getMu());
        $changed[$resultSet->getId()] = true;
      }
    }
  }
}