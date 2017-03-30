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
  private $resultTupleId1;
  private $resultTupleId2;
  private $answerSessionId;
  
  function __construct($threeCompareAnswerId, $time, $answer, $resultTupleId1, $resultTupleId2, $answerSessionId) {
    $this->threeCompareAnswerId = $threeCompareAnswerId;
    $this->time = $time;
    $this->answer = $answer;
    $this->resultTupleId1 = $resultTupleId1;
    $this->resultTupleId2 = $resultTupleId2;
    $this->answerSessionId = $answerSessionId;
  }
  
  function getKeyValueDict() {
    $dict = array();
    $dict['threeCompareAnswerId'] = $this->threeCompareAnswerId;
    $dict['time'] = $this->time;
    $dict['answer'] = $this->answer;
    $dict['resultTupleId1'] = $this->resultTupleId1;
    $dict['resultTupleId2'] = $this->resultTupleId2;
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
  
  function getResultTupleId1(){
    return $this->resultTupleId1;
  }
  
  function setResultTupleId1($resultTupleId1){
    $this->resultTupleId1 = $resultTupleId1;
  }
  
  function getResultTupleId2(){
    return $this->resultTupleId2;
  }
  
  function setResultTupleId2($resultTupleId2){
    $this->resultTupleId2 = $resultTupleId2;
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
  const RESULT_TUPLE_ID1 = "resultTupleId1";
  const RESULT_TUPLE_ID2 = "resultTupleId2";
  const ANSWER_SESSION_ID = "answerSessionId";
}
