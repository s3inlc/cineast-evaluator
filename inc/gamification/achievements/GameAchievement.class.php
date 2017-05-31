<?php
use DBA\Achievement;
use DBA\Game;
use DBA\OrderFilter;
use DBA\Player;
use DBA\QueryFilter;
use DBA\TwoCompareAnswer;

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
   * @param $player Player
   * @return int progress in %
   */
  abstract function getProgress($player);
  
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
  function alreadyReached($player) {
    global $FACTORIES;
    
    $qF1 = new QueryFilter(Achievement::PLAYER_ID, $player->getId(), "=");
    $qF2 = new QueryFilter(Achievement::ACHIEVEMENT_NAME, $this->getIdentifier(), "=");
    $achievement = $FACTORIES::getAchievementFactory()->filter(array($FACTORIES::FILTER => array($qF1, $qF2)), true);
    if ($achievement != null) {
      return true; // he already reached it so he can't get it twice
    }
    return false;
  }
  
  /**
   * @return bool true if the description is hidden when not achieved
   */
  function getIsHidden() {
    return false;
  }
  
  ############ STATS FUNCTIONS SECTION START ##########
  
  /**
   * @param $player Player
   * @return int
   */
  public function getNumInvitedPlayers($player) {
    global $FACTORIES;
    
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
    return $count;
  }
  
  /**
   * @param $player Player
   * @return int
   */
  public function getTotalAnswers($player) {
    global $FACTORIES;
    
    $qF = new QueryFilter(Game::PLAYER_ID, $player->getId(), "=");
    $answerSessions = $FACTORIES::getAnswerSessionFactory()->filter(array($FACTORIES::FILTER => $qF));
    $total = 0;
    foreach ($answerSessions as $answerSession) {
      $qF = new QueryFilter(TwoCompareAnswer::ANSWER_SESSION_ID, $answerSession->getId(), "=");
      $total += $FACTORIES::getTwoCompareAnswerFactory()->countFilter(array($FACTORIES::FILTER => $qF));
    }
    return $total;
  }
  
  /**
   * @param $player player
   * @return int
   */
  public function getMaxDaysInRow($player) {
    global $FACTORIES;
    
    $oF = new OrderFilter(Game::FINISHED_TIME, "DESC");
    $qF = new QueryFilter(Game::PLAYER_ID, $player->getId(), "=");
    $games = $FACTORIES::getGameFactory()->filter(array($FACTORIES::FILTER => $qF, $FACTORIES::ORDER => $oF));
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
    return $count;
  }
  
  /**
   * @param $player Player
   * @return int
   */
  public function getMaxGamesInDay($player) {
    global $FACTORIES;
    
    $oF = new OrderFilter(Game::FINISHED_TIME, "DESC");
    $qF = new QueryFilter(Game::PLAYER_ID, $player->getId(), "=");
    $games = $FACTORIES::getGameFactory()->filter(array($FACTORIES::FILTER => $qF, $FACTORIES::ORDER => $oF));
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
    return $maxCount;
  }
  
  /**
   * @return int
   */
  public function getGamesInRow() {
    if (!isset($_SESSION['gamesInRow'])) {
      return 0;
    }
    return $_SESSION['gamesInRow'] + 1;
  }
  
  /**
   * @param $player Player
   * @return int
   */
  public function getTotalScore($player) {
    global $FACTORIES;
    
    $qF = new QueryFilter(Game::PLAYER_ID, $player->getId(), "=");
    $games = $FACTORIES::getGameFactory()->filter(array($FACTORIES::FILTER => $qF));
    $total = 0;
    foreach ($games as $game) {
      $total += $game->getFullScore();
    }
    return $total;
  }
}