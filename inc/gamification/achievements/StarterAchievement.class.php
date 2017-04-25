<?php
use DBA\Achievement;
use DBA\Player;
use DBA\QueryFilter;

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 25.04.17
 * Time: 15:31
 */
class StarterGameAchievement extends GameAchievement {
  
  /**
   * @return string
   */
  function getAchievementName() {
    return "Starter";
  }
  
  /**
   * @param $player Player
   * @return bool
   */
  function isReachedByPlayer($player) {
    global $FACTORIES;
    
    if ($player != null) {
      $qF1 = new QueryFilter(Achievement::PLAYER_ID, $player->getId(), "=");
      $qF2 = new QueryFilter(Achievement::ACHIEVEMENT_NAME, $this->getAchievementName(), "=");
      $achievement = $FACTORIES::getAchievementFactory()->filter(array($FACTORIES::FILTER => array($qF1, $qF2)), true);
      if ($achievement != null) {
        return false; // he already reached it so he can't get it twice
      }
    }
    
    // this achievement is reached when at least one game is finished
    $answerSession = $FACTORIES::getAnswerSessionFactory()->get($_SESSION['answerSessionId']);
    if ($answerSession != null && $answerSession->getIsOpen() == 0) {
      return true;
    }
    return false;
  }
  
  /**
   * @return string
   */
  function getAchievementImage() {
    return "placeholder"; // TODO: add image
  }
  
  /**
   * @return float
   */
  function getMultiplicatorGain() {
    return 1.1;
  }
}