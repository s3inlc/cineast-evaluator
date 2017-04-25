<?php
use DBA\AnswerSession;
use DBA\Player;

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 25.04.17
 * Time: 15:27
 */
class AchievementTester {
  /**
   * @var GameAchievement[]
   */
  private $ACHIEVEMENTS = array();
  
  public function __construct() {
    $this->ACHIEVEMENTS[] = new StarterGameAchievement();
  }
  
  /**
   * If player is not set, it will return all achievements which match exactly for the current answer session
   * otherwise it will return the ones which were reached newly for this player
   * @param $player Player
   * @return GameAchievement[]
   */
  public function getAchievements($player = null) {
    $reached = array();
    foreach ($this->ACHIEVEMENTS as $achievement) {
      if ($achievement->isReachedByPlayer($player)) {
        $reached[] = $achievement;
      }
    }
    return $reached;
  }
}