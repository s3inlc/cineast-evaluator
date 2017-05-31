<?php
use DBA\Player;

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 25.04.17
 * Time: 15:31
 */
class NameAchievement extends GameAchievement {
  
  /**
   * @return string
   */
  function getAchievementName() {
    return "I'm unique";
  }
  
  function getIsHidden() {
    return true;
  }
  
  /**
   * @param $player Player
   * @return bool
   */
  function isReachedByPlayer($player) {
    if ($player == null || $this->alreadyReached($player)) {
      return false;
    }
    
    if ($player->getIsInitialName() == 0) {
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
    return "name";
  }
  
  /**
   * @return string
   */
  function getDescription() {
    return "Change your username from the default one.<br>Gives 5% extra score";
  }
}