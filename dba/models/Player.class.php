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
  private $email;
  private $affiliateKey;
  private $affiliatedBy;
  
  function __construct($playerId, $playerName, $email, $affiliateKey, $affiliatedBy) {
    $this->playerId = $playerId;
    $this->playerName = $playerName;
    $this->email = $email;
    $this->affiliateKey = $affiliateKey;
    $this->affiliatedBy = $affiliatedBy;
  }
  
  function getKeyValueDict() {
    $dict = array();
    $dict['playerId'] = $this->playerId;
    $dict['playerName'] = $this->playerName;
    $dict['email'] = $this->email;
    $dict['affiliateKey'] = $this->affiliateKey;
    $dict['affiliatedBy'] = $this->affiliatedBy;
    
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
  
  function getEmail(){
    return $this->email;
  }
  
  function setEmail($email){
    $this->email = $email;
  }
  
  function getAffiliateKey(){
    return $this->affiliateKey;
  }
  
  function setAffiliateKey($affiliateKey){
    $this->affiliateKey = $affiliateKey;
  }
  
  function getAffiliatedBy(){
    return $this->affiliatedBy;
  }
  
  function setAffiliatedBy($affiliatedBy){
    $this->affiliatedBy = $affiliatedBy;
  }

  const PLAYER_ID = "playerId";
  const PLAYER_NAME = "playerName";
  const EMAIL = "email";
  const AFFILIATE_KEY = "affiliateKey";
  const AFFILIATED_BY = "affiliatedBy";
}
