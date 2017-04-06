<?php
use DBA\AnswerSession;
use DBA\ResultTuple;

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 06.04.17
 * Time: 15:05
 */
class MultivariantGauss {
  private $mu;
  private $sigma; // array containing the values of the diagonal sparse matrix for sigma
  
  /**
   * MultivariantGauss constructor.
   * @param $resultSets ResultTuple[]
   * @param $excludedAnswerSession AnswerSession
   */
  public function __construct($resultSets, $excludedAnswerSession = null) {
    $gaussians = array();
    foreach ($resultSets as $resultSet) {
      $gaussian = new SimpleGauss($resultSet, $excludedAnswerSession);
      if ($gaussian->isValid()) {
        $gaussians[] = $gaussian;
      }
    }
    $this->mu = array();
    $this->sigma = array();
    foreach ($gaussians as $gaussian) {
      $this->mu[] = $gaussian->getMu();
      $this->sigma[] = $gaussian->getSigma();
    }
  }
  
  /**
   * @param $answers int[]
   * @return float probability of these answers
   */
  public function getProbability($answers) {
    $quot = 1 / (pow(2 * pi(), sizeof($this->sigma) / 2) * pow($this->det(), 1 / 2));
    return $quot * exp($this->calculateExp($answers));
  }
  
  /**
   * returns the determinant of sigma
   *
   * @return float
   */
  private function det() {
    $val = 1;
    foreach ($this->sigma as $sig) {
      $val *= $sig;
    }
    return $val;
  }
  
  /**
   * @param $x int[]
   * @return float
   */
  private function calculateExp($x) {
    $part = array();
    for ($i = 0; $i < sizeof($x); $i++) {
      $part[$i] = $x[$i] - $this->mu[$i];
    }
    
    $firstMult = array();
    for ($i = 0; $i < sizeof($this->sigma); $i++) {
      $firstMult[$i] = $this->sigma[$i] * $part[$i];
    }
    
    $scalar = 0;
    for ($i = 0; $i < sizeof($this->sigma); $i++) {
      $scalar += $part[$i] * $firstMult[$i];
    }
    return -1 / 2 * $scalar;
  }
}