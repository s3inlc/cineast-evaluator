<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 02.01.17
 * Time: 23:57
 */

namespace DBA;

class Player extends AbstractModel {
  private $playerId;
  private $playerName;
  
  function __construct($playerId, $playerName) {
    $this->playerId = $playerId;
    $this->playerName = $playerName;
  }
  
  function getKeyValueDict() {
    $dict = array();
    $dict['playerId'] = $this->playerId;
    $dict['playerName'] = $this->playerName;
    
    return $dict;
  }
  
  function getPrimaryKey() {
    return "playerId";
  }
  
  function getPrimaryKeyValue() {
    return $this->playerId;
  }
  
  function getId() {
    return $this->playerId;
  }
  
  function setId($id) {
    $this->playerId = $id;
  }
  
  function getPlayerName(){
    return $this->playerName;
  }
  
  function setPlayerName($playerName){
    $this->playerName = $playerName;
  }

  const PLAYER_ID = "playerId";
  const PLAYER_NAME = "playerName";
}
