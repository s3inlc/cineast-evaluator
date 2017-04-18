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
  private $sigma;
  private $mu;
  private $isFinal;
  
  function __construct($resultTupleId, $objectId1, $objectId2, $sigma, $mu, $isFinal) {
    $this->resultTupleId = $resultTupleId;
    $this->objectId1 = $objectId1;
    $this->objectId2 = $objectId2;
    $this->sigma = $sigma;
    $this->mu = $mu;
    $this->isFinal = $isFinal;
  }
  
  function getKeyValueDict() {
    $dict = array();
    $dict['resultTupleId'] = $this->resultTupleId;
    $dict['objectId1'] = $this->objectId1;
    $dict['objectId2'] = $this->objectId2;
    $dict['sigma'] = $this->sigma;
    $dict['mu'] = $this->mu;
    $dict['isFinal'] = $this->isFinal;
    
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
  
  function getSigma(){
    return $this->sigma;
  }
  
  function setSigma($sigma){
    $this->sigma = $sigma;
  }
  
  function getMu(){
    return $this->mu;
  }
  
  function setMu($mu){
    $this->mu = $mu;
  }
  
  function getIsFinal(){
    return $this->isFinal;
  }
  
  function setIsFinal($isFinal){
    $this->isFinal = $isFinal;
  }

  const RESULT_TUPLE_ID = "resultTupleId";
  const OBJECT_ID1 = "objectId1";
  const OBJECT_ID2 = "objectId2";
  const SIGMA = "sigma";
  const MU = "mu";
  const IS_FINAL = "isFinal";
}
