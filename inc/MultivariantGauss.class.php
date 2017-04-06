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
      $gaussians[] = new SimpleGauss($resultSet, $excludedAnswerSession);
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
    $quot = 1 / (pow(2 * pi(), $this->getValidSize($this->sigma) / 2) * pow($this->det(), 1 / 2));
    return $quot * exp($this->calculateExp($answers));
  }
  
  private function getValidsize($vector){
    $count = 0;
    foreach($vector as $val){
      if($val != -1){
        $count++;
      }
    }
    return $count;
  }
  
  /**
   * returns the determinant of sigma
   *
   * @return float
   */
  private function det() {
    $val = 1;
    foreach ($this->sigma as $sig) {
      if($sig == -1){
        continue;
      }
      else if($sig == 0){
        $sig = 0.00001;
      }
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
      if($this->mu[$i] == -1){
        $part[$i] = -1;
        continue;
      }
      $part[$i] = $x[$i] - $this->mu[$i];
    }
    
    $firstMult = array();
    for ($i = 0; $i < sizeof($this->sigma); $i++) {
      $firstMult[$i] = $this->sigma[$i] * $part[$i];
    }
    
    $scalar = 0;
    for ($i = 0; $i < sizeof($this->sigma); $i++) {
      if($part[$i] == -1){
        continue;
      }
      $scalar += $part[$i] * $firstMult[$i];
    }
    return -1 / 2 * $scalar;
  }
}