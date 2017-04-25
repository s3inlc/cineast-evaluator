<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 02.01.17
 * Time: 23:57
 */

namespace DBA;

class Achievement extends AbstractModel {
  private $achievementId;
  private $playerId;
  private $achievementName;
  private $time;
  
  function __construct($achievementId, $playerId, $achievementName, $time) {
    $this->achievementId = $achievementId;
    $this->playerId = $playerId;
    $this->achievementName = $achievementName;
    $this->time = $time;
  }
  
  function getKeyValueDict() {
    $dict = array();
    $dict['achievementId'] = $this->achievementId;
    $dict['playerId'] = $this->playerId;
    $dict['achievementName'] = $this->achievementName;
    $dict['time'] = $this->time;
    
    return $dict;
  }
  
  function getPrimaryKey() {
    return "achievementId";
  }
  
  function getPrimaryKeyValue() {
    return $this->achievementId;
  }
  
  function getId() {
    return $this->achievementId;
  }
  
  function setId($id) {
    $this->achievementId = $id;
  }
  
  function getPlayerId(){
    return $this->playerId;
  }
  
  function setPlayerId($playerId){
    $this->playerId = $playerId;
  }
  
  function getAchievementName(){
    return $this->achievementName;
  }
  
  function setAchievementName($achievementName){
    $this->achievementName = $achievementName;
  }
  
  function getTime(){
    return $this->time;
  }
  
  function setTime($time){
    $this->time = $time;
  }

  const ACHIEVEMENT_ID = "achievementId";
  const PLAYER_ID = "playerId";
  const ACHIEVEMENT_NAME = "achievementName";
  const TIME = "time";
}
