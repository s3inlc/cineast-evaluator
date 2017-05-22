<?php
use DBA\Game;
use DBA\Player;
use DBA\QueryFilter;

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 25.04.17
 * Time: 15:31
 */
class AffiliateLevel3Achievement extends GameAchievement {
  
  /**
   * @return string
   */
  function getAchievementName() {
    return "Inviting Level 3";
  }
  
  /**
   * @param $player Player
   * @return bool
   */
  function isReachedByPlayer($player) {
    global $FACTORIES;
    
    if ($player == null || $this->alreadyReached($player)) {
      return false;
    }
    
    // this achievement is reached when the user answered 100 questions
    $qF = new QueryFilter(Player::AFFILIATED_BY, $player->getId(), "=");
    $affiliated = $FACTORIES::getPlayerFactory()->filter(array($FACTORIES::FILTER => $qF));
    $count = 0;
    foreach ($affiliated as $p) {
      $qF = new QueryFilter(Game::PLAYER_ID, $p->getId(), "=");
      $num = $FACTORIES::getGameFactory()->countFilter(array($FACTORIES::FILTER => $qF));
      if ($num >= 1) {
        $count++;
      }
    }
    if ($count >= 5) {
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
    return "invitingLevel3";
  }
  
  /**
   * @return string
   */
  function getDescription() {
    return "Invite 5 people to play " . GAME_NAME . " which play at least one game.<br>Gives 5% extra score";
  }
}