<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 02.01.17
 * Time: 23:57
 */

namespace DBA;

class ResultTuple extends AbstractModel {
  private $resultTupleId;
  private $objectId1;
  private $objectId2;
  private $similarity;
  private $certainty;
  
  function __construct($resultTupleId, $objectId1, $objectId2, $similarity, $certainty) {
    $this->resultTupleId = $resultTupleId;
    $this->objectId1 = $objectId1;
    $this->objectId2 = $objectId2;
    $this->similarity = $similarity;
    $this->certainty = $certainty;
  }
  
  function getKeyValueDict() {
    $dict = array();
    $dict['resultTupleId'] = $this->resultTupleId;
    $dict['objectId1'] = $this->objectId1;
    $dict['objectId2'] = $this->objectId2;
    $dict['similarity'] = $this->similarity;
    $dict['certainty'] = $this->certainty;
    
    return $dict;
  }
  
  function getPrimaryKey() {
    return "resultTupleId";
  }
  
  function getPrimaryKeyValue() {
    return $this->resultTupleId;
  }
  
  function getId() {
    return $this->resultTupleId;
  }
  
  function setId($id) {
    $this->resultTupleId = $id;
  }
  
  function getObjectId1(){
    return $this->objectId1;
  }
  
  function setObjectId1($objectId1){
    $this->objectId1 = $objectId1;
  }
  
  function getObjectId2(){
    return $this->objectId2;
  }
  
  function setObjectId2($objectId2){
    $this->objectId2 = $objectId2;
  }
  
  function getSimilarity(){
    return $this->similarity;
  }
  
  function setSimilarity($similarity){
    $this->similarity = $similarity;
  }
  
  function getCertainty(){
    return $this->certainty;
  }
  
  function setCertainty($certainty){
    $this->certainty = $certainty;
  }

  const RESULT_TUPLE_ID = "resultTupleId";
  const OBJECT_ID1 = "objectId1";
  const OBJECT_ID2 = "objectId2";
  const SIMILARITY = "similarity";
  const CERTAINTY = "certainty";
}
