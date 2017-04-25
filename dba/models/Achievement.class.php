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
  private $name;
  private $time;
  
  function __construct($achievementId, $playerId, $name, $time) {
    $this->achievementId = $achievementId;
    $this->playerId = $playerId;
    $this->name = $name;
    $this->time = $time;
  }
  
  function getKeyValueDict() {
    $dict = array();
    $dict['achievementId'] = $this->achievementId;
    $dict['playerId'] = $this->playerId;
    $dict['name'] = $this->name;
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
  
  function getName(){
    return $this->name;
  }
  
  function setName($name){
    $this->name = $name;
  }
  
  function getTime(){
    return $this->time;
  }
  
  function setTime($time){
    $this->time = $time;
  }

  const ACHIEVEMENT_ID = "achievementId";
  const PLAYER_ID = "playerId";
  const NAME = "name";
  const TIME = "time";
}
