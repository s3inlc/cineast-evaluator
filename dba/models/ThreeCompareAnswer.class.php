<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 02.01.17
 * Time: 23:57
 */

namespace DBA;

class ThreeCompareAnswer extends AbstractModel {
  private $threeCompareAnswerId;
  private $time;
  private $answer;
  private $mediaObjectId1;
  private $mediaObjectId2;
  private $answerSessionId;
  
  function __construct($threeCompareAnswerId, $time, $answer, $mediaObjectId1, $mediaObjectId2, $answerSessionId) {
    $this->threeCompareAnswerId = $threeCompareAnswerId;
    $this->time = $time;
    $this->answer = $answer;
    $this->mediaObjectId1 = $mediaObjectId1;
    $this->mediaObjectId2 = $mediaObjectId2;
    $this->answerSessionId = $answerSessionId;
  }
  
  function getKeyValueDict() {
    $dict = array();
    $dict['threeCompareAnswerId'] = $this->threeCompareAnswerId;
    $dict['time'] = $this->time;
    $dict['answer'] = $this->answer;
    $dict['mediaObjectId1'] = $this->mediaObjectId1;
    $dict['mediaObjectId2'] = $this->mediaObjectId2;
    $dict['answerSessionId'] = $this->answerSessionId;
    
    return $dict;
  }
  
  function getPrimaryKey() {
    return "threeCompareAnswerId";
  }
  
  function getPrimaryKeyValue() {
    return $this->threeCompareAnswerId;
  }
  
  function getId() {
    return $this->threeCompareAnswerId;
  }
  
  function setId($id) {
    $this->threeCompareAnswerId = $id;
  }
  
  function getTime(){
    return $this->time;
  }
  
  function setTime($time){
    $this->time = $time;
  }
  
  function getAnswer(){
    return $this->answer;
  }
  
  function setAnswer($answer){
    $this->answer = $answer;
  }
  
  function getMediaObjectId1(){
    return $this->mediaObjectId1;
  }
  
  function setMediaObjectId1($mediaObjectId1){
    $this->mediaObjectId1 = $mediaObjectId1;
  }
  
  function getMediaObjectId2(){
    return $this->mediaObjectId2;
  }
  
  function setMediaObjectId2($mediaObjectId2){
    $this->mediaObjectId2 = $mediaObjectId2;
  }
  
  function getAnswerSessionId(){
    return $this->answerSessionId;
  }
  
  function setAnswerSessionId($answerSessionId){
    $this->answerSessionId = $answerSessionId;
  }

  const THREE_COMPARE_ANSWER_ID = "threeCompareAnswerId";
  const TIME = "time";
  const ANSWER = "answer";
  const MEDIA_OBJECT_ID1 = "mediaObjectId1";
  const MEDIA_OBJECT_ID2 = "mediaObjectId2";
  const ANSWER_SESSION_ID = "answerSessionId";
}
