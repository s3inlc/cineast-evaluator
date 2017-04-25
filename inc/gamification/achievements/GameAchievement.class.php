<?php
use DBA\Player;

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
   * @param $player Player
   * @return bool
   */
  abstract function isReachedByPlayer($player);
  
  /**
   * @return string
   */
  abstract function getAchievementImage();
  
  /**
   * @return float
   */
  abstract function getMultiplicatorGain();
}