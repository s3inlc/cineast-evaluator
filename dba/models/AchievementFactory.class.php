<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 02.01.17
 * Time: 23:57
 */

namespace DBA;

class AchievementFactory extends AbstractModelFactory {
  function getModelName() {
    return "Achievement";
  }
  
  function getModelTable() {
    return "Achievement";
  }
  
  function isCachable() {
    return false;
  }
  
  function getCacheValidTime() {
    return -1;
  }

  /**
   * @return Achievement
   */
  function getNullObject() {
    $o = new Achievement(-1, null, null, null);
    return $o;
  }

  /**
   * @param string $pk
   * @param array $dict
   * @return Achievement
   */
  function createObjectFromDict($pk, $dict) {
    $o = new Achievement($pk, $dict['playerId'], $dict['name'], $dict['time']);
    return $o;
  }

  /**
   * @param array $options
   * @param bool $single
   * @return Achievement|Achievement[]
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
      return Util::cast(parent::filter($options, $single), Achievement::class);
    }
    $objects = parent::filter($options, $single);
    if($join){
      return $objects;
    }
    $models = array();
    foreach($objects as $object){
      $models[] = Util::cast($object, Achievement::class);
    }
    return $models;
  }

  /**
   * @param string $pk
   * @return Achievement
   */
  function get($pk) {
    return Util::cast(parent::get($pk), Achievement::class);
  }

  /**
   * @param Achievement $model
   * @return Achievement
   */
  function save($model) {
    return Util::cast(parent::save($model), Achievement::class);
  }
}