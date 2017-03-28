<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 02.01.17
 * Time: 23:57
 */

namespace DBA;

class QueryResultTuple extends AbstractModel {
  private $queryResultTupleId;
  private $queryId;
  private $resultTupleId;
  private $score;
  private $rank;
  
  function __construct($queryResultTupleId, $queryId, $resultTupleId, $score, $rank) {
    $this->queryResultTupleId = $queryResultTupleId;
    $this->queryId = $queryId;
    $this->resultTupleId = $resultTupleId;
    $this->score = $score;
    $this->rank = $rank;
  }
  
  function getKeyValueDict() {
    $dict = array();
    $dict['queryResultTupleId'] = $this->queryResultTupleId;
    $dict['queryId'] = $this->queryId;
    $dict['resultTupleId'] = $this->resultTupleId;
    $dict['score'] = $this->score;
    $dict['rank'] = $this->rank;
    
    return $dict;
  }
  
  function getPrimaryKey() {
    return "queryResultTupleId";
  }
  
  function getPrimaryKeyValue() {
    return $this->queryResultTupleId;
  }
  
  function getId() {
    return $this->queryResultTupleId;
  }
  
  function setId($id) {
    $this->queryResultTupleId = $id;
  }
  
  function getQueryId(){
    return $this->queryId;
  }
  
  function setQueryId($queryId){
    $this->queryId = $queryId;
  }
  
  function getResultTupleId(){
    return $this->resultTupleId;
  }
  
  function setResultTupleId($resultTupleId){
    $this->resultTupleId = $resultTupleId;
  }
  
  function getScore(){
    return $this->score;
  }
  
  function setScore($score){
    $this->score = $score;
  }
  
  function getRank(){
    return $this->rank;
  }
  
  function setRank($rank){
    $this->rank = $rank;
  }

  const QUERY_RESULT_TUPLE_ID = "queryResultTupleId";
  const QUERY_ID = "queryId";
  const RESULT_TUPLE_ID = "resultTupleId";
  const SCORE = "score";
  const RANK = "rank";
}
