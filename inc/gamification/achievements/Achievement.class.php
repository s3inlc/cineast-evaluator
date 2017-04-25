<?php
use DBA\Player;

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 25.04.17
 * Time: 14:33
 */
abstract class Achievement {
  /**
   * @return string
   */
  abstract function getAchievementName();
  
  /**
   * @param $player Player
   * @return bool
   */
  abstract function isReachedByPlayer($player);
  
  /**
   * @return string
   */
  abstract function getAchievementImage();
}