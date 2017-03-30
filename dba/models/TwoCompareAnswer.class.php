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
  private $resultTupleId;
  private $answer;
  private $answerSessionId;
  
  function __construct($twoCompareAnswerId, $time, $resultTupleId, $answer, $answerSessionId) {
    $this->twoCompareAnswerId = $twoCompareAnswerId;
    $this->time = $time;
    $this->resultTupleId = $resultTupleId;
    $this->answer = $answer;
    $this->answerSessionId = $answerSessionId;
  }
  
  function getKeyValueDict() {
    $dict = array();
    $dict['twoCompareAnswerId'] = $this->twoCompareAnswerId;
    $dict['time'] = $this->time;
    $dict['resultTupleId'] = $this->resultTupleId;
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
  
  function getResultTupleId(){
    return $this->resultTupleId;
  }
  
  function setResultTupleId($resultTupleId){
    $this->resultTupleId = $resultTupleId;
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
  const RESULT_TUPLE_ID = "resultTupleId";
  const ANSWER = "answer";
  const ANSWER_SESSION_ID = "answerSessionId";
}
