<?php
use DBA\Player;

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
      if($this->alreadyReached($player)){
        return false;
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