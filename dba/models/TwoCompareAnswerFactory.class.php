<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 02.01.17
 * Time: 23:57
 */

namespace DBA;

class TwoCompareAnswerFactory extends AbstractModelFactory {
  function getModelName() {
    return "TwoCompareAnswer";
  }
  
  function getModelTable() {
    return "TwoCompareAnswer";
  }
  
  function isCachable() {
    return false;
  }
  
  function getCacheValidTime() {
    return -1;
  }

  /**
   * @return TwoCompareAnswer
   */
  function getNullObject() {
    $o = new TwoCompareAnswer(-1, null, null, null, null);
    return $o;
  }

  /**
   * @param string $pk
   * @param array $dict
   * @return TwoCompareAnswer
   */
  function createObjectFromDict($pk, $dict) {
    $o = new TwoCompareAnswer($pk, $dict['time'], $dict['resultTupleId'], $dict['answer'], $dict['answerSessionId']);
    return $o;
  }

  /**
   * @param array $options
   * @param bool $single
   * @return TwoCompareAnswer|TwoCompareAnswer[]
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
      return Util::cast(parent::filter($options, $single), TwoCompareAnswer::class);
    }
    $objects = parent::filter($options, $single);
    if($join){
      return $objects;
    }
    $models = array();
    foreach($objects as $object){
      $models[] = Util::cast($object, TwoCompareAnswer::class);
    }
    return $models;
  }

  /**
   * @param string $pk
   * @return TwoCompareAnswer
   */
  function get($pk) {
    return Util::cast(parent::get($pk), TwoCompareAnswer::class);
  }

  /**
   * @param TwoCompareAnswer $model
   * @return TwoCompareAnswer
   */
  function save($model) {
    return Util::cast(parent::save($model), TwoCompareAnswer::class);
  }
}