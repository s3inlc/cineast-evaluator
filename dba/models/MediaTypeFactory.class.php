<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 02.01.17
 * Time: 23:57
 */

namespace DBA;

class MediaTypeFactory extends AbstractModelFactory {
  function getModelName() {
    return "MediaType";
  }
  
  function getModelTable() {
    return "MediaType";
  }
  
  function isCachable() {
    return false;
  }
  
  function getCacheValidTime() {
    return -1;
  }

  /**
   * @return MediaType
   */
  function getNullObject() {
    $o = new MediaType(-1, null, null);
    return $o;
  }

  /**
   * @param string $pk
   * @param array $dict
   * @return MediaType
   */
  function createObjectFromDict($pk, $dict) {
    $o = new MediaType($pk, $dict['typeName'], $dict['extension']);
    return $o;
  }

  /**
   * @param array $options
   * @param bool $single
   * @return MediaType|MediaType[]
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
      return Util::cast(parent::filter($options, $single), MediaType::class);
    }
    $objects = parent::filter($options, $single);
    if($join){
      return $objects;
    }
    $models = array();
    foreach($objects as $object){
      $models[] = Util::cast($object, MediaType::class);
    }
    return $models;
  }

  /**
   * @param string $pk
   * @return MediaType
   */
  function get($pk) {
    return Util::cast(parent::get($pk), MediaType::class);
  }

  /**
   * @param MediaType $model
   * @return MediaType
   */
  function save($model) {
    return Util::cast(parent::save($model), MediaType::class);
  }
}