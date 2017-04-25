<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 02.01.17
 * Time: 23:57
 */

namespace DBA;

class QueryFactory extends AbstractModelFactory {
  function getModelName() {
    return "Query";
  }
  
  function getModelTable() {
    return "Query";
  }
  
  function isCachable() {
    return false;
  }
  
  function getCacheValidTime() {
    return -1;
  }

  /**
   * @return Query
   */
  function getNullObject() {
    $o = new Query(-1, null, null, null, null, null, null, null);
    return $o;
  }

  /**
   * @param string $pk
   * @param array $dict
   * @return Query
   */
  function createObjectFromDict($pk, $dict) {
    $o = new Query($pk, $dict['isClosed'], $dict['time'], $dict['displayName'], $dict['userId'], $dict['meta'], $dict['priority'], $dict['progress']);
    return $o;
  }

  /**
   * @param array $options
   * @param bool $single
   * @return Query|Query[]
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
      return Util::cast(parent::filter($options, $single), Query::class);
    }
    $objects = parent::filter($options, $single);
    if($join){
      return $objects;
    }
    $models = array();
    foreach($objects as $object){
      $models[] = Util::cast($object, Query::class);
    }
    return $models;
  }

  /**
   * @param string $pk
   * @return Query
   */
  function get($pk) {
    return Util::cast(parent::get($pk), Query::class);
  }

  /**
   * @param Query $model
   * @return Query
   */
  function save($model) {
    return Util::cast(parent::save($model), Query::class);
  }
}