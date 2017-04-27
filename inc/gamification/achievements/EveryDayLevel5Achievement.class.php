<?php
use DBA\Game;
use DBA\OrderFilter;
use DBA\Player;
use DBA\QueryFilter;

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 25.04.17
 * Time: 15:31
 */
class EveryDayLevel5Achievement extends GameAchievement {
  
  /**
   * @return string
   */
  function getAchievementName() {
    return "Every day a game Level 5";
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
    
    $oF = new OrderFilter(Game::FINISHED_TIME, "DESC");
    $qF = new QueryFilter(Game::PLAYER_ID, $player->getId(), "=");
    $games = $FACTORIES::getGameFactory()->filter(array($FACTORIES::FILTER => $qF, $FACTORIES::ORDER => $oF));
    if (sizeof($games) < 2) {
      return false;
    }
    $currentDay = strtotime("midnight", $games[0]->getFinishedTime());
    $nextDay = $currentDay + 3600 * 24;
    $count = 0;
    foreach ($games as $game) {
      if ($game->getFinishedTime() >= $currentDay && $game->getFinishedTime() < $nextDay) {
        $count++;
        $currentDay -= 3600 * 24;
        $nextDay -= 3600 * 24;
      }
    }
    
    if ($count >= 28) {
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
    return "gameDayLevel5";
  }
  
  /**
   * @return string
   */
  function getDescription() {
    return "Play at least one game per day for 4 weeks in row.<br>Gives 10% extra score";
  }
}