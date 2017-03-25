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
  private $matching;
  
  function __construct($queryResultTupleId, $queryId, $resultTupleId, $matching) {
    $this->queryResultTupleId = $queryResultTupleId;
    $this->queryId = $queryId;
    $this->resultTupleId = $resultTupleId;
    $this->matching = $matching;
  }
  
  function getKeyValueDict() {
    $dict = array();
    $dict['queryResultTupleId'] = $this->queryResultTupleId;
    $dict['queryId'] = $this->queryId;
    $dict['resultTupleId'] = $this->resultTupleId;
    $dict['matching'] = $this->matching;
    
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
  
  function getMatching(){
    return $this->matching;
  }
  
  function setMatching($matching){
    $this->matching = $matching;
  }

  const QUERY_RESULT_TUPLE_ID = "queryResultTupleId";
  const QUERY_ID = "queryId";
  const RESULT_TUPLE_ID = "resultTupleId";
  const MATCHING = "matching";
}
