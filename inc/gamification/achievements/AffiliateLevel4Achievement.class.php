<?php
use DBA\Player;

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 25.04.17
 * Time: 15:31
 */
class AffiliateLevel4Achievement extends GameAchievement {
  
  /**
   * @return string
   */
  function getAchievementName() {
    return "Inviting Level 4";
  }
  
  /**
   * @param $player Player
   * @return bool
   */
  function isReachedByPlayer($player) {
    if ($player == null || $this->alreadyReached($player)) {
      return false;
    }
    
    $count = $this->getNumInvitedPlayers($player);
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
    return "invitingLevel4";
  }
  
  /**
   * @return string
   */
  function getDescription() {
    return "Invite 10 people to play " . GAME_NAME . " which play at least one game.<br>Gives 10% extra score";
  }
  
  /**
   * @param $player Player
   * @return int progress in %
   */
  function getProgress($player) {
    if ($player == null) {
      return 0;
    }
    return floor(min(100, $this->getNumInvitedPlayers($player) / 10 * 100));
  }
}