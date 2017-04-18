<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 02.01.17
 * Time: 23:57
 */

namespace DBA;

class ResultTupleFactory extends AbstractModelFactory {
  function getModelName() {
    return "ResultTuple";
  }
  
  function getModelTable() {
    return "ResultTuple";
  }
  
  function isCachable() {
    return false;
  }
  
  function getCacheValidTime() {
    return -1;
  }

  /**
   * @return ResultTuple
   */
  function getNullObject() {
    $o = new ResultTuple(-1, null, null, null, null, null);
    return $o;
  }

  /**
   * @param string $pk
   * @param array $dict
   * @return ResultTuple
   */
  function createObjectFromDict($pk, $dict) {
    $o = new ResultTuple($pk, $dict['objectId1'], $dict['objectId2'], $dict['sigma'], $dict['mu'], $dict['isFinal']);
    return $o;
  }

  /**
   * @param array $options
   * @param bool $single
   * @return ResultTuple|ResultTuple[]
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
      return Util::cast(parent::filter($options, $single), ResultTuple::class);
    }
    $objects = parent::filter($options, $single);
    if($join){
      return $objects;
    }
    $models = array();
    foreach($objects as $object){
      $models[] = Util::cast($object, ResultTuple::class);
    }
    return $models;
  }

  /**
   * @param string $pk
   * @return ResultTuple
   */
  function get($pk) {
    return Util::cast(parent::get($pk), ResultTuple::class);
  }

  /**
   * @param ResultTuple $model
   * @return ResultTuple
   */
  function save($model) {
    return Util::cast(parent::save($model), ResultTuple::class);
  }
}