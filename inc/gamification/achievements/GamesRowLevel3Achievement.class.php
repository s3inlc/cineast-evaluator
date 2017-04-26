<?php
use DBA\Player;

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 25.04.17
 * Time: 15:31
 */
class GamesRowLevel3Achievement extends GameAchievement {
  
  /**
   * @return string
   */
  function getAchievementName() {
    return "Games in a row Level 3";
  }
  
  /**
   * @param $player Player
   * @return bool
   */
  function isReachedByPlayer($player) {
    if ($player == null || $this->alreadyReached($player)) {
      return false;
    }
    
    if ($_SESSION['gamesInRow'] + 1 >= 10) {
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
    return "gameRowLevel3";
  }
  
  /**
   * @return string
   */
  function getDescription() {
    return "Complete 10 games in a row.<br>Gives 10% extra score";
  }
}