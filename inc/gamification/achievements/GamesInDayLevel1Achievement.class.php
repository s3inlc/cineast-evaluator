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
class GamesInDayLevel1 extends GameAchievement {
  
  /**
   * @return string
   */
  function getAchievementName() {
    return "Games per day";
  }
  
  function getIsHidden() {
    return false;
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
    if (sizeof($games) < 5) {
      return false;
    }
    $currentDay = strtotime("midnight", $games[0]->getFinishedTime());
    $nextDay = $currentDay + 3600 * 24;
    $maxCount = 0;
    $currentCount = 0;
    foreach ($games as $game) {
      if ($game->getFinishedTime() >= $currentDay && $game->getFinishedTime() < $nextDay) {
        $currentCount++;
      }
      else {
        $currentCount = 0;
        $currentDay -= 3600 * 24;
        $nextDay -= 3600 * 24;
      }
      if ($currentCount > $maxCount) {
        $maxCount = $currentCount;
      }
    }
    
    if ($maxCount >= 5) {
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
    return "gamesPerDayLevel1";
  }
  
  /**
   * @return string
   */
  function getDescription() {
    return "Play 5 games during one day.<br>Gives 5% extra score";
  }
}