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
    $this->ACHIEVEMENTS[] = new StarterAchievement();
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
        $achievement = new Achievement(0, $player->getId(), $reach->getIdentifier(), time());
        $FACTORIES::getAchievementFactory()->save($achievement);
      }
    }
    
    return $reached;
  }
  
  /**
   * Get all achievements ordered by name
   * @return GameAchievement[]
   */
  public function getAllAchievemens() {
    /** @var $all GameAchievement[] */
    $all = array();
    foreach ($this->ACHIEVEMENTS as $achievement) {
      if (sizeof($all) == 0) {
        $all[0] = $achievement;
        continue;
      }
      $inserted = false;
      for ($i = 0; $i < sizeof($all); $i++) {
        if (strcasecmp($achievement->getIdentifier(), $all[$i]->getIdentifier()) < 0) {
          $start = sizeof($all);
          for ($j = $start; $j > $i; $j--) {
            $all[$j] = $all[$j - 1];
          }
          $all[$i] = $achievement;
          $inserted = true;
          break;
        }
      }
      if (!$inserted) {
        $all[] = $achievement;
      }
    }
    return $all;
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