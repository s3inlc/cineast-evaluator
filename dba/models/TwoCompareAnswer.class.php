<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 02.01.17
 * Time: 23:57
 */

namespace DBA;

class TwoCompareAnswer extends AbstractModel {
  private $twoCompareAnswerId;
  private $time;
  private $mediaObjectId;
  private $answer;
  private $answerSessionId;
  
  function __construct($twoCompareAnswerId, $time, $mediaObjectId, $answer, $answerSessionId) {
    $this->twoCompareAnswerId = $twoCompareAnswerId;
    $this->time = $time;
    $this->mediaObjectId = $mediaObjectId;
    $this->answer = $answer;
    $this->answerSessionId = $answerSessionId;
  }
  
  function getKeyValueDict() {
    $dict = array();
    $dict['twoCompareAnswerId'] = $this->twoCompareAnswerId;
    $dict['time'] = $this->time;
    $dict['mediaObjectId'] = $this->mediaObjectId;
    $dict['answer'] = $this->answer;
    $dict['answerSessionId'] = $this->answerSessionId;
    
    return $dict;
  }
  
  function getPrimaryKey() {
    return "twoCompareAnswerId";
  }
  
  function getPrimaryKeyValue() {
    return $this->twoCompareAnswerId;
  }
  
  function getId() {
    return $this->twoCompareAnswerId;
  }
  
  function setId($id) {
    $this->twoCompareAnswerId = $id;
  }
  
  function getTime(){
    return $this->time;
  }
  
  function setTime($time){
    $this->time = $time;
  }
  
  function getMediaObjectId(){
    return $this->mediaObjectId;
  }
  
  function setMediaObjectId($mediaObjectId){
    $this->mediaObjectId = $mediaObjectId;
  }
  
  function getAnswer(){
    return $this->answer;
  }
  
  function setAnswer($answer){
    $this->answer = $answer;
  }
  
  function getAnswerSessionId(){
    return $this->answerSessionId;
  }
  
  function setAnswerSessionId($answerSessionId){
    $this->answerSessionId = $answerSessionId;
  }

  const TWO_COMPARE_ANSWER_ID = "twoCompareAnswerId";
  const TIME = "time";
  const MEDIA_OBJECT_ID = "mediaObjectId";
  const ANSWER = "answer";
  const ANSWER_SESSION_ID = "answerSessionId";
}
