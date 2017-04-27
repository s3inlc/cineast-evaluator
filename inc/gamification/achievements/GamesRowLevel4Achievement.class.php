<?php
use DBA\Player;

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 25.04.17
 * Time: 15:31
 */
class GamesRowLevel4Achievement extends GameAchievement {
  
  /**
   * @return string
   */
  function getAchievementName() {
    return "Games in a row Level Addicted";
  }
  
  /**
   * @param $player Player
   * @return bool
   */
  function isReachedByPlayer($player) {
    if ($player == null || $this->alreadyReached($player)) {
      return false;
    }
    
    if (isset($_SESSION['gamesInRow']) && $_SESSION['gamesInRow'] + 1 >= 20) {
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
    return 1.15;
  }
  
  /**
   * @return string
   */
  function getIdentifier() {
    return "gameRowLevel4";
  }
  
  /**
   * @return string
   */
  function getDescription() {
    return "Complete 20 games in a row.<br>Gives 15% extra score";
  }
}