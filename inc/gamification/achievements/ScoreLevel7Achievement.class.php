<?php
use DBA\Player;

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 25.04.17
 * Time: 15:31
 */
class ScoreLevel7Achievement extends GameAchievement {
  
  /**
   * @return string
   */
  function getAchievementName() {
    return "Total Score Level 7";
  }
  
  /**
   * @param $player Player
   * @return bool
   */
  function isReachedByPlayer($player) {
    if ($player == null || $this->alreadyReached($player)) {
      return false;
    }
    
    $total = $this->getTotalScore($player);
    if ($total >= 500000000) {
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
    return 1.1;
  }
  
  /**
   * @return string
   */
  function getIdentifier() {
    return "scoreLevel7";
  }
  
  /**
   * @return string
   */
  function getDescription() {
    return "Get 500'000'000 score points in total of all your games.<br>Gives 10% extra score";
  }
  
  /**
   * @param $player Player
   * @return int progress in %
   */
  function getProgress($player) {
    if ($player == null) {
      return 0;
    }
    return floor(min(100, $this->getTotalScore($player) / 500000000 * 100));
  }
}