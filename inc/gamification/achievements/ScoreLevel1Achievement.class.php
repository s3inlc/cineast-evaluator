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
class ScoreLevel1Achievement extends GameAchievement {
  
  /**
   * @return string
   */
  function getAchievementName() {
    return "Total Score Level 1";
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
    
    // this achievement is reached when a total score of 100'000 is reached
    $qF = new QueryFilter(Game::PLAYER_ID, $player->getId(), "=");
    $games = $FACTORIES::getGameFactory()->filter(array($FACTORIES::FILTER => $qF));
    $total = 0;
    foreach ($games as $game) {
      $total += $game->getFullScore();
    }
    if ($total > 100000) {
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
    return 1.05;
  }
}