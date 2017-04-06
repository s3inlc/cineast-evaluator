<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 02.01.17
 * Time: 23:57
 */

namespace DBA;

class ValidationFactory extends AbstractModelFactory {
  function getModelName() {
    return "Validation";
  }
  
  function getModelTable() {
    return "Validation";
  }
  
  function isCachable() {
    return false;
  }
  
  function getCacheValidTime() {
    return -1;
  }

  /**
   * @return Validation
   */
  function getNullObject() {
    $o = new Validation(-1, null, null, null, null, null);
    return $o;
  }

  /**
   * @param string $pk
   * @param array $dict
   * @return Validation
   */
  function createObjectFromDict($pk, $dict) {
    $o = new Validation($pk, $dict['answerSessionId'], $dict['validator'], $dict['event'], $dict['bonus'], $dict['malus']);
    return $o;
  }

  /**
   * @param array $options
   * @param bool $single
   * @return Validation|Validation[]
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
      return Util::cast(parent::filter($options, $single), Validation::class);
    }
    $objects = parent::filter($options, $single);
    if($join){
      return $objects;
    }
    $models = array();
    foreach($objects as $object){
      $models[] = Util::cast($object, Validation::class);
    }
    return $models;
  }

  /**
   * @param string $pk
   * @return Validation
   */
  function get($pk) {
    return Util::cast(parent::get($pk), Validation::class);
  }

  /**
   * @param Validation $model
   * @return Validation
   */
  function save($model) {
    return Util::cast(parent::save($model), Validation::class);
  }
}