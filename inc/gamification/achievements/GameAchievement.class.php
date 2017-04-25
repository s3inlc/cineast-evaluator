<?php
use DBA\Achievement;
use DBA\Player;
use DBA\QueryFilter;

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 25.04.17
 * Time: 14:33
 */
abstract class GameAchievement {
  /**
   * @return string
   */
  abstract function getAchievementName();
  
  /**
   * @return string
   */
  abstract function getIdentifier();
  
  /**
   * @return string
   */
  abstract function getDescription();
  
  /**
   * @param $player Player
   * @return bool
   */
  abstract function isReachedByPlayer($player);
  
  /**
   * @return string
   */
  abstract function getAchievementImage();
  
  /**
   * @return float
   */
  abstract function getMultiplicatorGain();
  
  /**
   * @param $player Player
   * @return bool
   */
  function alreadyReached($player){
    global $FACTORIES;
    
    $qF1 = new QueryFilter(Achievement::PLAYER_ID, $player->getId(), "=");
    $qF2 = new QueryFilter(Achievement::ACHIEVEMENT_NAME, $this->getAchievementName(), "=");
    $achievement = $FACTORIES::getAchievementFactory()->filter(array($FACTORIES::FILTER => array($qF1, $qF2)), true);
    if ($achievement != null) {
      return true; // he already reached it so he can't get it twice
    }
    return false;
  }
}