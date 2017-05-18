<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 02.01.17
 * Time: 23:57
 */

namespace DBA;

class Microworker extends AbstractModel {
  private $microworkerId;
  private $microworkerBatchId;
  private $token;
  private $isLocked;
  private $timeStarted;
  private $timeClosed;
  private $surveyCode;
  private $isConfirmed;
  
  function __construct($microworkerId, $microworkerBatchId, $token, $isLocked, $timeStarted, $timeClosed, $surveyCode, $isConfirmed) {
    $this->microworkerId = $microworkerId;
    $this->microworkerBatchId = $microworkerBatchId;
    $this->token = $token;
    $this->isLocked = $isLocked;
    $this->timeStarted = $timeStarted;
    $this->timeClosed = $timeClosed;
    $this->surveyCode = $surveyCode;
    $this->isConfirmed = $isConfirmed;
  }
  
  function getKeyValueDict() {
    $dict = array();
    $dict['microworkerId'] = $this->microworkerId;
    $dict['microworkerBatchId'] = $this->microworkerBatchId;
    $dict['token'] = $this->token;
    $dict['isLocked'] = $this->isLocked;
    $dict['timeStarted'] = $this->timeStarted;
    $dict['timeClosed'] = $this->timeClosed;
    $dict['surveyCode'] = $this->surveyCode;
    $dict['isConfirmed'] = $this->isConfirmed;
    
    return $dict;
  }
  
  function getPrimaryKey() {
    return "microworkerId";
  }
  
  function getPrimaryKeyValue() {
    return $this->microworkerId;
  }
  
  function getId() {
    return $this->microworkerId;
  }
  
  function setId($id) {
    $this->microworkerId = $id;
  }
  
  function getMicroworkerBatchId(){
    return $this->microworkerBatchId;
  }
  
  function setMicroworkerBatchId($microworkerBatchId){
    $this->microworkerBatchId = $microworkerBatchId;
  }
  
  function getToken(){
    return $this->token;
  }
  
  function setToken($token){
    $this->token = $token;
  }
  
  function getIsLocked(){
    return $this->isLocked;
  }
  
  function setIsLocked($isLocked){
    $this->isLocked = $isLocked;
  }
  
  function getTimeStarted(){
    return $this->timeStarted;
  }
  
  function setTimeStarted($timeStarted){
    $this->timeStarted = $timeStarted;
  }
  
  function getTimeClosed(){
    return $this->timeClosed;
  }
  
  function setTimeClosed($timeClosed){
    $this->timeClosed = $timeClosed;
  }
  
  function getSurveyCode(){
    return $this->surveyCode;
  }
  
  function setSurveyCode($surveyCode){
    $this->surveyCode = $surveyCode;
  }
  
  function getIsConfirmed(){
    return $this->isConfirmed;
  }
  
  function setIsConfirmed($isConfirmed){
    $this->isConfirmed = $isConfirmed;
  }

  const MICROWORKER_ID = "microworkerId";
  const MICROWORKER_BATCH_ID = "microworkerBatchId";
  const TOKEN = "token";
  const IS_LOCKED = "isLocked";
  const TIME_STARTED = "timeStarted";
  const TIME_CLOSED = "timeClosed";
  const SURVEY_CODE = "surveyCode";
  const IS_CONFIRMED = "isConfirmed";
}
