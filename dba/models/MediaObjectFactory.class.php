<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 02.01.17
 * Time: 23:57
 */

namespace DBA;

class MediaObjectFactory extends AbstractModelFactory {
  function getModelName() {
    return "MediaObject";
  }
  
  function getModelTable() {
    return "MediaObject";
  }
  
  function isCachable() {
    return false;
  }
  
  function getCacheValidTime() {
    return -1;
  }

  /**
   * @return MediaObject
   */
  function getNullObject() {
    $o = new MediaObject(-1, null, null, null, null, null, null);
    return $o;
  }

  /**
   * @param string $pk
   * @param array $dict
   * @return MediaObject
   */
  function createObjectFromDict($pk, $dict) {
    $o = new MediaObject($pk, $dict['mediaTypeId'], $dict['filename'], $dict['time'], $dict['checksum'], $dict['source'], $dict['original']);
    return $o;
  }

  /**
   * @param array $options
   * @param bool $single
   * @return MediaObject|MediaObject[]
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
      return Util::cast(parent::filter($options, $single), MediaObject::class);
    }
    $objects = parent::filter($options, $single);
    if($join){
      return $objects;
    }
    $models = array();
    foreach($objects as $object){
      $models[] = Util::cast($object, MediaObject::class);
    }
    return $models;
  }

  /**
   * @param string $pk
   * @return MediaObject
   */
  function get($pk) {
    return Util::cast(parent::get($pk), MediaObject::class);
  }

  /**
   * @param MediaObject $model
   * @return MediaObject
   */
  function save($model) {
    return Util::cast(parent::save($model), MediaObject::class);
  }
}