<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 02.01.17
 * Time: 23:57
 */

namespace DBA;

class ThreeCompareAnswerFactory extends AbstractModelFactory {
  function getModelName() {
    return "ThreeCompareAnswer";
  }
  
  function getModelTable() {
    return "ThreeCompareAnswer";
  }
  
  function isCachable() {
    return false;
  }
  
  function getCacheValidTime() {
    return -1;
  }

  /**
   * @return ThreeCompareAnswer
   */
  function getNullObject() {
    $o = new ThreeCompareAnswer(-1, null, null, null, null, null);
    return $o;
  }

  /**
   * @param string $pk
   * @param array $dict
   * @return ThreeCompareAnswer
   */
  function createObjectFromDict($pk, $dict) {
    $o = new ThreeCompareAnswer($pk, $dict['time'], $dict['answer'], $dict['resultTupleId1'], $dict['resultTupleId2'], $dict['answerSessionId']);
    return $o;
  }

  /**
   * @param array $options
   * @param bool $single
   * @return ThreeCompareAnswer|ThreeCompareAnswer[]
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
      return Util::cast(parent::filter($options, $single), ThreeCompareAnswer::class);
    }
    $objects = parent::filter($options, $single);
    if($join){
      return $objects;
    }
    $models = array();
    foreach($objects as $object){
      $models[] = Util::cast($object, ThreeCompareAnswer::class);
    }
    return $models;
  }

  /**
   * @param string $pk
   * @return ThreeCompareAnswer
   */
  function get($pk) {
    return Util::cast(parent::get($pk), ThreeCompareAnswer::class);
  }

  /**
   * @param ThreeCompareAnswer $model
   * @return ThreeCompareAnswer
   */
  function save($model) {
    return Util::cast(parent::save($model), ThreeCompareAnswer::class);
  }
}