<?php
use DBA\Achievement;
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
    $this->ACHIEVEMENTS[] = new ScoreLevel1Achievement();
  }
  
  /**
   * If player is not set, it will return all achievements which match exactly for the current answer session
   * otherwise it will return the ones which were reached newly for this player
   * @param $player Player
   * @return GameAchievement[]
   */
  public function getAchievements($player = null) {
    global $FACTORIES;
    
    /** @var $reached GameAchievement[] */
    $reached = array();
    foreach ($this->ACHIEVEMENTS as $achievement) {
      if ($achievement->isReachedByPlayer($player)) {
        $reached[] = $achievement;
      }
    }
    
    if ($player != null) {
      foreach ($reached as $reach) {
        $achievement = new Achievement(0, $player->getId(), $reach->getAchievementName(), time());
        $FACTORIES::getAchievementFactory()->save($achievement);
      }
    }
    
    return $reached;
  }
  
  /**
   * @param $name string
   * @return GameAchievement
   */
  public function getAchievement($name) {
    foreach ($this->ACHIEVEMENTS as $achievement) {
      if ($achievement->getAchievementName() == $name) {
        return $achievement;
      }
    }
    return null;
  }
}