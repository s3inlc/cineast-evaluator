<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 02.01.17
 * Time: 23:57
 */

namespace DBA;

class AnswerSession extends AbstractModel {
  private $answerSessionId;
  private $microworkerId;
  private $userId;
  private $playerId;
  private $currentValidity;
  private $isOpen;
  private $timeOpened;
  private $userAgentIp;
  private $userAgentHeader;
  
  function __construct($answerSessionId, $microworkerId, $userId, $playerId, $currentValidity, $isOpen, $timeOpened, $userAgentIp, $userAgentHeader) {
    $this->answerSessionId = $answerSessionId;
    $this->microworkerId = $microworkerId;
    $this->userId = $userId;
    $this->playerId = $playerId;
    $this->currentValidity = $currentValidity;
    $this->isOpen = $isOpen;
    $this->timeOpened = $timeOpened;
    $this->userAgentIp = $userAgentIp;
    $this->userAgentHeader = $userAgentHeader;
  }
  
  function getKeyValueDict() {
    $dict = array();
    $dict['answerSessionId'] = $this->answerSessionId;
    $dict['microworkerId'] = $this->microworkerId;
    $dict['userId'] = $this->userId;
    $dict['playerId'] = $this->playerId;
    $dict['currentValidity'] = $this->currentValidity;
    $dict['isOpen'] = $this->isOpen;
    $dict['timeOpened'] = $this->timeOpened;
    $dict['userAgentIp'] = $this->userAgentIp;
    $dict['userAgentHeader'] = $this->userAgentHeader;
    
    return $dict;
  }
  
  function getPrimaryKey() {
    return "answerSessionId";
  }
  
  function getPrimaryKeyValue() {
    return $this->answerSessionId;
  }
  
  function getId() {
    return $this->answerSessionId;
  }
  
  function setId($id) {
    $this->answerSessionId = $id;
  }
  
  function getMicroworkerId(){
    return $this->microworkerId;
  }
  
  function setMicroworkerId($microworkerId){
    $this->microworkerId = $microworkerId;
  }
  
  function getUserId(){
    return $this->userId;
  }
  
  function setUserId($userId){
    $this->userId = $userId;
  }
  
  function getPlayerId(){
    return $this->playerId;
  }
  
  function setPlayerId($playerId){
    $this->playerId = $playerId;
  }
  
  function getCurrentValidity(){
    return $this->currentValidity;
  }
  
  function setCurrentValidity($currentValidity){
    $this->currentValidity = $currentValidity;
  }
  
  function getIsOpen(){
    return $this->isOpen;
  }
  
  function setIsOpen($isOpen){
    $this->isOpen = $isOpen;
  }
  
  function getTimeOpened(){
    return $this->timeOpened;
  }
  
  function setTimeOpened($timeOpened){
    $this->timeOpened = $timeOpened;
  }
  
  function getUserAgentIp(){
    return $this->userAgentIp;
  }
  
  function setUserAgentIp($userAgentIp){
    $this->userAgentIp = $userAgentIp;
  }
  
  function getUserAgentHeader(){
    return $this->userAgentHeader;
  }
  
  function setUserAgentHeader($userAgentHeader){
    $this->userAgentHeader = $userAgentHeader;
  }

  const ANSWER_SESSION_ID = "answerSessionId";
  const MICROWORKER_ID = "microworkerId";
  const USER_ID = "userId";
  const PLAYER_ID = "playerId";
  const CURRENT_VALIDITY = "currentValidity";
  const IS_OPEN = "isOpen";
  const TIME_OPENED = "timeOpened";
  const USER_AGENT_IP = "userAgentIp";
  const USER_AGENT_HEADER = "userAgentHeader";
}
