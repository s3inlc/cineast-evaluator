<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 02.01.17
 * Time: 23:57
 */

namespace DBA;

class MicroworkerFactory extends AbstractModelFactory {
  function getModelName() {
    return "Microworker";
  }
  
  function getModelTable() {
    return "Microworker";
  }
  
  function isCachable() {
    return false;
  }
  
  function getCacheValidTime() {
    return -1;
  }

  /**
   * @return Microworker
   */
  function getNullObject() {
    $o = new Microworker(-1, null, null, null, null, null, null, null, null);
    return $o;
  }

  /**
   * @param string $pk
   * @param array $dict
   * @return Microworker
   */
  function createObjectFromDict($pk, $dict) {
    $o = new Microworker($pk, $dict['microworkerBatchId'], $dict['token'], $dict['isLocked'], $dict['timeStarted'], $dict['timeClosed'], $dict['surveyCode'], $dict['isConfirmed'], $dict['workerId']);
    return $o;
  }

  /**
   * @param array $options
   * @param bool $single
   * @return Microworker|Microworker[]
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
      return Util::cast(parent::filter($options, $single), Microworker::class);
    }
    $objects = parent::filter($options, $single);
    if($join){
      return $objects;
    }
    $models = array();
    foreach($objects as $object){
      $models[] = Util::cast($object, Microworker::class);
    }
    return $models;
  }

  /**
   * @param string $pk
   * @return Microworker
   */
  function get($pk) {
    return Util::cast(parent::get($pk), Microworker::class);
  }

  /**
   * @param Microworker $model
   * @return Microworker
   */
  function save($model) {
    return Util::cast(parent::save($model), Microworker::class);
  }
}