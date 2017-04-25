<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 02.01.17
 * Time: 23:57
 */

namespace DBA;

class Game extends AbstractModel {
  private $gameId;
  private $playerId;
  private $answerSessionId;
  private $finishedTime;
  private $gameScore;
  private $fullScore;
  
  function __construct($gameId, $playerId, $answerSessionId, $finishedTime, $gameScore, $fullScore) {
    $this->gameId = $gameId;
    $this->playerId = $playerId;
    $this->answerSessionId = $answerSessionId;
    $this->finishedTime = $finishedTime;
    $this->gameScore = $gameScore;
    $this->fullScore = $fullScore;
  }
  
  function getKeyValueDict() {
    $dict = array();
    $dict['gameId'] = $this->gameId;
    $dict['playerId'] = $this->playerId;
    $dict['answerSessionId'] = $this->answerSessionId;
    $dict['finishedTime'] = $this->finishedTime;
    $dict['gameScore'] = $this->gameScore;
    $dict['fullScore'] = $this->fullScore;
    
    return $dict;
  }
  
  function getPrimaryKey() {
    return "gameId";
  }
  
  function getPrimaryKeyValue() {
    return $this->gameId;
  }
  
  function getId() {
    return $this->gameId;
  }
  
  function setId($id) {
    $this->gameId = $id;
  }
  
  function getPlayerId(){
    return $this->playerId;
  }
  
  function setPlayerId($playerId){
    $this->playerId = $playerId;
  }
  
  function getAnswerSessionId(){
    return $this->answerSessionId;
  }
  
  function setAnswerSessionId($answerSessionId){
    $this->answerSessionId = $answerSessionId;
  }
  
  function getFinishedTime(){
    return $this->finishedTime;
  }
  
  function setFinishedTime($finishedTime){
    $this->finishedTime = $finishedTime;
  }
  
  function getGameScore(){
    return $this->gameScore;
  }
  
  function setGameScore($gameScore){
    $this->gameScore = $gameScore;
  }
  
  function getFullScore(){
    return $this->fullScore;
  }
  
  function setFullScore($fullScore){
    $this->fullScore = $fullScore;
  }

  const GAME_ID = "gameId";
  const PLAYER_ID = "playerId";
  const ANSWER_SESSION_ID = "answerSessionId";
  const FINISHED_TIME = "finishedTime";
  const GAME_SCORE = "gameScore";
  const FULL_SCORE = "fullScore";
}
