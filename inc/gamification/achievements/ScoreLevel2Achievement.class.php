<?php
use DBA\Player;

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 25.04.17
 * Time: 15:31
 */
class ScoreLevel2Achievement extends GameAchievement {
  
  /**
   * @return string
   */
  function getAchievementName() {
    return "Total Score Level 2";
  }
  
  /**
   * @param $player Player
   * @return bool
   */
  function isReachedByPlayer($player) {
    global $FACTORIES;
    
    if ($player == null || $this->alreadyReached($player)) {
      return false;
    }
    
    $total = $this->getTotalScore($player);
    if ($total >= 5000000) {
      return true;
    }
    return false;
  }
  
  /**
   * @return string
   */
  function getAchievementImage() {
    return "success.png"; // TODO: add image
  }
  
  /**
   * @return float
   */
  function getMultiplicatorGain() {
    return 1.05;
  }
  
  /**
   * @return string
   */
  function getIdentifier() {
    return "scoreLevel2";
  }
  
  /**
   * @return string
   */
  function getDescription() {
    return "Get 5'000'000 score points in total of all your games.<br>Gives 5% extra score";
  }
  
  /**
   * @param $player Player
   * @return int progress in %
   */
  function getProgress($player) {
    if ($player == null) {
      return 0;
    }
    return floor(min(100, $this->getTotalScore($player) / 5000000 * 100));
  }
}