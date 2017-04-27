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
class ScoreLevel4Achievement extends GameAchievement {
  
  /**
   * @return string
   */
  function getAchievementName() {
    return "Total Score Level 4";
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
    
    // this achievement is reached when a total score of 5'000'000 is reached
    $qF = new QueryFilter(Game::PLAYER_ID, $player->getId(), "=");
    $games = $FACTORIES::getGameFactory()->filter(array($FACTORIES::FILTER => $qF));
    $total = 0;
    foreach ($games as $game) {
      $total += $game->getFullScore();
    }
    if ($total >= 50000000) {
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
    return "scoreLevel4";
  }
  
  /**
   * @return string
   */
  function getDescription() {
    return "Get 50'000'000 score points in total of all your games.<br>Gives 10% extra score";
  }
}