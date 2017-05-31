<?php
use DBA\Player;

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 25.04.17
 * Time: 15:31
 */
class AnswersLevel1Achievement extends GameAchievement {
  
  /**
   * @return string
   */
  function getAchievementName() {
    return "Answered Questions Level 1";
  }
  
  /**
   * @param $player Player
   * @return bool
   */
  function isReachedByPlayer($player) {
    if ($player == null || $this->alreadyReached($player)) {
      return false;
    }
    
    $total = $this->getTotalAnswers($player);
    if ($total >= 100) {
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
    return "answersLevel1";
  }
  
  /**
   * @return string
   */
  function getDescription() {
    return "Answer at least 100 questions.<br>Gives 5% extra score";
  }
  
  /**
   * @param $player Player
   * @return int progress in %
   */
  function getProgress($player) {
    if ($player == null) {
      return 0;
    }
    return floor(min(100, $this->getTotalAnswers($player)));
  }
}