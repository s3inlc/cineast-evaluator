<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 02.01.17
 * Time: 23:57
 */

namespace DBA;

class MicroworkerBatchFactory extends AbstractModelFactory {
  function getModelName() {
    return "MicroworkerBatch";
  }
  
  function getModelTable() {
    return "MicroworkerBatch";
  }
  
  function isCachable() {
    return false;
  }
  
  function getCacheValidTime() {
    return -1;
  }

  /**
   * @return MicroworkerBatch
   */
  function getNullObject() {
    $o = new MicroworkerBatch(-1, null);
    return $o;
  }

  /**
   * @param string $pk
   * @param array $dict
   * @return MicroworkerBatch
   */
  function createObjectFromDict($pk, $dict) {
    $o = new MicroworkerBatch($pk, $dict['timeCreated']);
    return $o;
  }

  /**
   * @param array $options
   * @param bool $single
   * @return MicroworkerBatch|MicroworkerBatch[]
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
      return Util::cast(parent::filter($options, $single), MicroworkerBatch::class);
    }
    $objects = parent::filter($options, $single);
    if($join){
      return $objects;
    }
    $models = array();
    foreach($objects as $object){
      $models[] = Util::cast($object, MicroworkerBatch::class);
    }
    return $models;
  }

  /**
   * @param string $pk
   * @return MicroworkerBatch
   */
  function get($pk) {
    return Util::cast(parent::get($pk), MicroworkerBatch::class);
  }

  /**
   * @param MicroworkerBatch $model
   * @return MicroworkerBatch
   */
  function save($model) {
    return Util::cast(parent::save($model), MicroworkerBatch::class);
  }
}