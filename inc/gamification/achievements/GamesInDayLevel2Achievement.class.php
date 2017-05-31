<?php
use DBA\Player;

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 25.04.17
 * Time: 15:31
 */
class GamesInDayLevel2Achievement extends GameAchievement {
  
  /**
   * @return string
   */
  function getAchievementName() {
    return "Games per day Level 2";
  }
  
  function getIsHidden() {
    return false;
  }
  
  /**
   * @param $player Player
   * @return bool
   */
  function isReachedByPlayer($player) {
    if ($player == null || $this->alreadyReached($player)) {
      return false;
    }
    
    $count = $this->getMaxGamesInDay($player);
    if ($count >= 10) {
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
    return "gamesPerDayLevel2";
  }
  
  /**
   * @return string
   */
  function getDescription() {
    return "Play 10 games during one day.<br>Gives 5% extra score";
  }
  
  /**
   * @param $player Player
   * @return int progress in %
   */
  function getProgress($player) {
    if ($player == null) {
      return 0;
    }
    return floor(min(100, $this->getMaxGamesInDay($player) / 10 * 100));
  }
}