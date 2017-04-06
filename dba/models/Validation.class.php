<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 02.01.17
 * Time: 23:57
 */

namespace DBA;

class Validation extends AbstractModel {
  private $validationId;
  private $answerSessionId;
  private $validator;
  private $event;
  private $bonus;
  private $malus;
  
  function __construct($validationId, $answerSessionId, $validator, $event, $bonus, $malus) {
    $this->validationId = $validationId;
    $this->answerSessionId = $answerSessionId;
    $this->validator = $validator;
    $this->event = $event;
    $this->bonus = $bonus;
    $this->malus = $malus;
  }
  
  function getKeyValueDict() {
    $dict = array();
    $dict['validationId'] = $this->validationId;
    $dict['answerSessionId'] = $this->answerSessionId;
    $dict['validator'] = $this->validator;
    $dict['event'] = $this->event;
    $dict['bonus'] = $this->bonus;
    $dict['malus'] = $this->malus;
    
    return $dict;
  }
  
  function getPrimaryKey() {
    return "validationId";
  }
  
  function getPrimaryKeyValue() {
    return $this->validationId;
  }
  
  function getId() {
    return $this->validationId;
  }
  
  function setId($id) {
    $this->validationId = $id;
  }
  
  function getAnswerSessionId(){
    return $this->answerSessionId;
  }
  
  function setAnswerSessionId($answerSessionId){
    $this->answerSessionId = $answerSessionId;
  }
  
  function getValidator(){
    return $this->validator;
  }
  
  function setValidator($validator){
    $this->validator = $validator;
  }
  
  function getEvent(){
    return $this->event;
  }
  
  function setEvent($event){
    $this->event = $event;
  }
  
  function getBonus(){
    return $this->bonus;
  }
  
  function setBonus($bonus){
    $this->bonus = $bonus;
  }
  
  function getMalus(){
    return $this->malus;
  }
  
  function setMalus($malus){
    $this->malus = $malus;
  }

  const VALIDATION_ID = "validationId";
  const ANSWER_SESSION_ID = "answerSessionId";
  const VALIDATOR = "validator";
  const EVENT = "event";
  const BONUS = "bonus";
  const MALUS = "malus";
}
