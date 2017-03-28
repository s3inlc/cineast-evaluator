<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 02.01.17
 * Time: 23:57
 */

namespace DBA;

class QueryResultTupleFactory extends AbstractModelFactory {
  function getModelName() {
    return "QueryResultTuple";
  }
  
  function getModelTable() {
    return "QueryResultTuple";
  }
  
  function isCachable() {
    return false;
  }
  
  function getCacheValidTime() {
    return -1;
  }

  /**
   * @return QueryResultTuple
   */
  function getNullObject() {
    $o = new QueryResultTuple(-1, null, null, null, null);
    return $o;
  }

  /**
   * @param string $pk
   * @param array $dict
   * @return QueryResultTuple
   */
  function createObjectFromDict($pk, $dict) {
    $o = new QueryResultTuple($pk, $dict['queryId'], $dict['resultTupleId'], $dict['score'], $dict['rank']);
    return $o;
  }

  /**
   * @param array $options
   * @param bool $single
   * @return QueryResultTuple|QueryResultTuple[]
   */
  function filter($options, $single = false) {
    $join = false;
    if (array_key_exists('join', $options)) {
      $join = true;
    }
    if($single){
      if($join){
        return parent::filter($options, $single);
      }
      return Util::cast(parent::filter($options, $single), QueryResultTuple::class);
    }
    $objects = parent::filter($options, $single);
    if($join){
      return $objects;
    }
    $models = array();
    foreach($objects as $object){
      $models[] = Util::cast($object, QueryResultTuple::class);
    }
    return $models;
  }

  /**
   * @param string $pk
   * @return QueryResultTuple
   */
  function get($pk) {
    return Util::cast(parent::get($pk), QueryResultTuple::class);
  }

  /**
   * @param QueryResultTuple $model
   * @return QueryResultTuple
   */
  function save($model) {
    return Util::cast(parent::save($model), QueryResultTuple::class);
  }
}