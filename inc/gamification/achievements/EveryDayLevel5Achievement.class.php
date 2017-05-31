<?php
use DBA\Player;

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 25.04.17
 * Time: 15:31
 */
class EveryDayLevel5Achievement extends GameAchievement {
  
  /**
   * @return string
   */
  function getAchievementName() {
    return "Every day a game Level 5";
  }
  
  /**
   * @param $player Player
   * @return bool
   */
  function isReachedByPlayer($player) {
    if ($player == null || $this->alreadyReached($player)) {
      return false;
    }
    
    $count = $this->getMaxDaysInRow($player);
    if ($count >= 28) {
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
    return "gameDayLevel5";
  }
  
  /**
   * @return string
   */
  function getDescription() {
    return "Play at least one game per day for 4 weeks in row.<br>Gives 10% extra score";
  }
  
  /**
   * @param $player Player
   * @return int progress in %
   */
  function getProgress($player) {
    if ($player == null) {
      return 0;
    }
    return floor(min(100, $this->getMaxDaysInRow($player) / 28 * 100));
  }
}