<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 02.01.17
 * Time: 23:57
 */

namespace DBA;

class OauthFactory extends AbstractModelFactory {
  function getModelName() {
    return "Oauth";
  }
  
  function getModelTable() {
    return "Oauth";
  }
  
  function isCachable() {
    return false;
  }
  
  function getCacheValidTime() {
    return -1;
  }

  /**
   * @return Oauth
   */
  function getNullObject() {
    $o = new Oauth(-1, null, null, null, null, null);
    return $o;
  }

  /**
   * @param string $pk
   * @param array $dict
   * @return Oauth
   */
  function createObjectFromDict($pk, $dict) {
    $o = new Oauth($pk, $dict['playerId'], $dict['type'], $dict['firstLogin'], $dict['lastLogin'], $dict['oauthIdentifier']);
    return $o;
  }

  /**
   * @param array $options
   * @param bool $single
   * @return Oauth|Oauth[]
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
      return Util::cast(parent::filter($options, $single), Oauth::class);
    }
    $objects = parent::filter($options, $single);
    if($join){
      return $objects;
    }
    $models = array();
    foreach($objects as $object){
      $models[] = Util::cast($object, Oauth::class);
    }
    return $models;
  }

  /**
   * @param string $pk
   * @return Oauth
   */
  function get($pk) {
    return Util::cast(parent::get($pk), Oauth::class);
  }

  /**
   * @param Oauth $model
   * @return Oauth
   */
  function save($model) {
    return Util::cast(parent::save($model), Oauth::class);
  }
}