<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 02.01.17
 * Time: 23:57
 */

namespace DBA;

class MicroworkerBatch extends AbstractModel {
  private $microworkerBatchId;
  private $timeCreated;
  
  function __construct($microworkerBatchId, $timeCreated) {
    $this->microworkerBatchId = $microworkerBatchId;
    $this->timeCreated = $timeCreated;
  }
  
  function getKeyValueDict() {
    $dict = array();
    $dict['microworkerBatchId'] = $this->microworkerBatchId;
    $dict['timeCreated'] = $this->timeCreated;
    
    return $dict;
  }
  
  function getPrimaryKey() {
    return "microworkerBatchId";
  }
  
  function getPrimaryKeyValue() {
    return $this->microworkerBatchId;
  }
  
  function getId() {
    return $this->microworkerBatchId;
  }
  
  function setId($id) {
    $this->microworkerBatchId = $id;
  }
  
  function getTimeCreated(){
    return $this->timeCreated;
  }
  
  function setTimeCreated($timeCreated){
    $this->timeCreated = $timeCreated;
  }

  const MICROWORKER_BATCH_ID = "microworkerBatchId";
  const TIME_CREATED = "timeCreated";
}
