<?php
use DBA\AnswerSession;
use DBA\JoinFilter;
use DBA\QueryFilter;
use DBA\ResultTuple;
use DBA\TwoCompareAnswer;

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 06.04.17
 * Time: 14:25
 */
class SimpleGauss {
  private $mu;
  private $sigma;
  
  /**
   * SimpleGauss constructor.
   * @param $tuple ResultTuple
   * @param $excludedAnswerSession AnswerSession
   */
  public function __construct($tuple, $excludedAnswerSession = null) {
    global $FACTORIES;
    
    $qF1 = new QueryFilter(TwoCompareAnswer::RESULT_TUPLE_ID, $tuple->getId(), "=");
    $qF2 = new QueryFilter(AnswerSession::IS_OPEN, 0, "=", $FACTORIES::getAnswerSessionFactory()); // only consider completed sessions
    $filters = array($qF1, $qF2);
    if ($excludedAnswerSession != null) {
      // we need to exclude this answer session
      $qF3 = new QueryFilter(AnswerSession::ANSWER_SESSION_ID, $excludedAnswerSession->getId(), "<>", $FACTORIES::getAnswerSessionFactory());
      $filters[] = $qF3;
    }
    $jF = new JoinFilter($FACTORIES::getAnswerSessionFactory(), TwoCompareAnswer::ANSWER_SESSION_ID, AnswerSession::ANSWER_SESSION_ID);
    $joined = $FACTORIES::getTwoCompareAnswerFactory()->filter(array($FACTORIES::FILTER => $filters, $FACTORIES::JOIN => $jF));
    
    if (sizeof($joined['TwoCompareAnswer']) < GAUSS_LIMIT) { // TODO: set limit which should be used that a gauss curve can be constructed
      $this->mu = -1;
      $this->sigma = -1;
      return;
    }
    
    $weightedSum = 0;
    $probabilitySum = 0;
    for ($i = 0; $i < sizeof($joined['TwoCompareAnswer']); $i++) {
      /** @var $twoCompareAnswer TwoCompareAnswer */
      $twoCompareAnswer = $joined['TwoCompareAnswer'][$i];
      /** @var $answerSession AnswerSession */
      $answerSession = $joined['AnswerSession'][$i];
      
      $weightedSum += $twoCompareAnswer->getAnswer() * $answerSession->getCurrentValidity();
      $probabilitySum += $answerSession->getCurrentValidity();
    }
    
    if ($probabilitySum == 0) {
      $this->mu = -1;
      $this->sigma = -1;
      return;
    }
    
    $this->mu = $weightedSum / $probabilitySum;
    
    $sigmaSum = 0;
    for ($i = 0; $i < sizeof($joined['TwoCompareAnswer']); $i++) {
      /** @var $twoCompareAnswer TwoCompareAnswer */
      $twoCompareAnswer = $joined['TwoCompareAnswer'][$i];
      
      $sigmaSum += ($twoCompareAnswer->getAnswer() - $this->mu) * ($twoCompareAnswer->getAnswer() - $this->mu);
    }
    
    $this->sigma = sqrt($sigmaSum / sizeof($joined['TwoCompareAnswer']));
  }
  
  /**
   * @return float -1 if sigma could not be calculated
   */
  public function getSigma() {
    return $this->sigma;
  }
  
  /**
   * @return float -1 if mu could not be calculated
   */
  public function getMu() {
    return $this->mu;
  }
  
  /**
   * @return bool
   */
  public function isValid() {
    return $this->mu != -1 && $this->sigma != -1;
  }
  
  /**
   * @param $answer int between 0 and 3
   * @return float probability for this answer
   */
  public function getProbability($answer) {
    if (!$this->isValid()) {
      return -1;
    }
    else if ($this->sigma == 0) {
      if ($answer == $this->mu) {
        return 1;
      }
      return 0;
    }
    $exponent = -1 / 2 * pow(($answer - $this->mu) / pow($this->sigma, 2), 2);
    return 1 / ($this->sigma * sqrt(2 * pi())) * exp($exponent);
  }
  
  public static function getStaticProbability($val, $sigma, $mu) {
    $exponent = -1 / 2 * pow(($val - $mu) / $sigma, 2);
    return 1 / ($sigma * sqrt(2 * pi())) * exp($exponent);
  }
  
  public static function generateCurve($sigma, $mu, $steps = array(500, 200), $range = array(array(0, 3)), $border = 60) {
    $im = imagecreatetruecolor($steps[0] + $border * 2, $steps[1] + $border);
    $bg = imagecolorallocate($im, 255, 255, 255);
    imagefilledrectangle($im, 0, 0, $steps[0] + $border * 2 - 1, $steps[1] + $border - 1, $bg);
    $black = imagecolorallocate($im, 0, 0, 0);
    $grey = imagecolorallocate($im, 150, 150, 150);
    imagerectangle($im, $border, 0, $steps[0] - 1 + $border, $steps[1] - 1, $black);
    
    // draw grey lines
    imageline($im, round($steps[0] / 3) + $border, 0, round($steps[0] / 3) + $border, $steps[1] - 1, $grey);
    imageline($im, round($steps[0] / 3 * 2) + $border, 0, round($steps[0] / 3 * 2) + $border, $steps[1] - 1, $grey);
    
    // draw answers
    imagestring($im, 2, $border - 40, $steps[1] + 2, 'No Similarity', $black);
    imagestring($im, 2, round($steps[0] / 3) + $border - 40, $steps[1] + 2, 'Slightly Similar', $black);
    imagestring($im, 2, round($steps[0] / 3 * 2) + $border - 40, $steps[1] + 2, 'Very Similar', $black);
    imagestring($im, 2, $steps[0] + $border - 40, $steps[1] + 2, 'Nearly Identical', $black);
    
    $pos = array(array(), array());
    $yMax = 0;
    for ($x = 0; $x < $steps[0]; $x++) {
      $xpos = $x * ($range[0][1] - $range[0][0]) / $steps[0];
      $ypos = SimpleGauss::getStaticProbability($xpos, $sigma, $mu);
      $pos[0][] = $xpos;
      $pos[1][] = $ypos;
      if ($ypos > $yMax) {
        $yMax = $ypos;
      }
    }
    
    $yMax += 0.1;
    
    // draw part
    for ($x = 0; $x < $steps[0]; $x++) {
      $y = round($pos[1][$x] * $steps[1] / ($yMax));
      imagesetpixel($im, $x + $border, $steps[1] - $y, $black);
    }
    
    ob_start();
    imagepng($im);
    $imageData = ob_get_contents();
    ob_end_clean();
    
    return $base64 = 'data:image/png;base64,' . base64_encode($imageData);
  }
}













