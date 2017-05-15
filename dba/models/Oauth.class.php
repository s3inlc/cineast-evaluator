<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 02.01.17
 * Time: 23:57
 */

namespace DBA;

class Oauth extends AbstractModel {
  private $oauthId;
  private $playerId;
  private $type;
  private $firstLogin;
  private $lastLogin;
  private $oauthIdentifier;
  
  function __construct($oauthId, $playerId, $type, $firstLogin, $lastLogin, $oauthIdentifier) {
    $this->oauthId = $oauthId;
    $this->playerId = $playerId;
    $this->type = $type;
    $this->firstLogin = $firstLogin;
    $this->lastLogin = $lastLogin;
    $this->oauthIdentifier = $oauthIdentifier;
  }
  
  function getKeyValueDict() {
    $dict = array();
    $dict['oauthId'] = $this->oauthId;
    $dict['playerId'] = $this->playerId;
    $dict['type'] = $this->type;
    $dict['firstLogin'] = $this->firstLogin;
    $dict['lastLogin'] = $this->lastLogin;
    $dict['oauthIdentifier'] = $this->oauthIdentifier;
    
    return $dict;
  }
  
  function getPrimaryKey() {
    return "oauthId";
  }
  
  function getPrimaryKeyValue() {
    return $this->oauthId;
  }
  
  function getId() {
    return $this->oauthId;
  }
  
  function setId($id) {
    $this->oauthId = $id;
  }
  
  function getPlayerId(){
    return $this->playerId;
  }
  
  function setPlayerId($playerId){
    $this->playerId = $playerId;
  }
  
  function getType(){
    return $this->type;
  }
  
  function setType($type){
    $this->type = $type;
  }
  
  function getFirstLogin(){
    return $this->firstLogin;
  }
  
  function setFirstLogin($firstLogin){
    $this->firstLogin = $firstLogin;
  }
  
  function getLastLogin(){
    return $this->lastLogin;
  }
  
  function setLastLogin($lastLogin){
    $this->lastLogin = $lastLogin;
  }
  
  function getOauthIdentifier(){
    return $this->oauthIdentifier;
  }
  
  function setOauthIdentifier($oauthIdentifier){
    $this->oauthIdentifier = $oauthIdentifier;
  }

  const OAUTH_ID = "oauthId";
  const PLAYER_ID = "playerId";
  const TYPE = "type";
  const FIRST_LOGIN = "firstLogin";
  const LAST_LOGIN = "lastLogin";
  const OAUTH_IDENTIFIER = "oauthIdentifier";
}
