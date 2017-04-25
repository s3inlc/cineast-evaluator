<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 02.01.17
 * Time: 23:57
 */

namespace DBA;

class Query extends AbstractModel {
  private $queryId;
  private $isClosed;
  private $time;
  private $displayName;
  private $userId;
  private $meta;
  private $priority;
  private $progress;
  
  function __construct($queryId, $isClosed, $time, $displayName, $userId, $meta, $priority, $progress) {
    $this->queryId = $queryId;
    $this->isClosed = $isClosed;
    $this->time = $time;
    $this->displayName = $displayName;
    $this->userId = $userId;
    $this->meta = $meta;
    $this->priority = $priority;
    $this->progress = $progress;
  }
  
  function getKeyValueDict() {
    $dict = array();
    $dict['queryId'] = $this->queryId;
    $dict['isClosed'] = $this->isClosed;
    $dict['time'] = $this->time;
    $dict['displayName'] = $this->displayName;
    $dict['userId'] = $this->userId;
    $dict['meta'] = $this->meta;
    $dict['priority'] = $this->priority;
    $dict['progress'] = $this->progress;
    
    return $dict;
  }
  
  function getPrimaryKey() {
    return "queryId";
  }
  
  function getPrimaryKeyValue() {
    return $this->queryId;
  }
  
  function getId() {
    return $this->queryId;
  }
  
  function setId($id) {
    $this->queryId = $id;
  }
  
  function getIsClosed(){
    return $this->isClosed;
  }
  
  function setIsClosed($isClosed){
    $this->isClosed = $isClosed;
  }
  
  function getTime(){
    return $this->time;
  }
  
  function setTime($time){
    $this->time = $time;
  }
  
  function getDisplayName(){
    return $this->displayName;
  }
  
  function setDisplayName($displayName){
    $this->displayName = $displayName;
  }
  
  function getUserId(){
    return $this->userId;
  }
  
  function setUserId($userId){
    $this->userId = $userId;
  }
  
  function getMeta(){
    return $this->meta;
  }
  
  function setMeta($meta){
    $this->meta = $meta;
  }
  
  function getPriority(){
    return $this->priority;
  }
  
  function setPriority($priority){
    $this->priority = $priority;
  }
  
  function getProgress(){
    return $this->progress;
  }
  
  function setProgress($progress){
    $this->progress = $progress;
  }

  const QUERY_ID = "queryId";
  const IS_CLOSED = "isClosed";
  const TIME = "time";
  const DISPLAY_NAME = "displayName";
  const USER_ID = "userId";
  const META = "meta";
  const PRIORITY = "priority";
  const PROGRESS = "progress";
}
