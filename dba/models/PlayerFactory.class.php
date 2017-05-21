<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 02.01.17
 * Time: 23:57
 */

namespace DBA;

class PlayerFactory extends AbstractModelFactory {
  function getModelName() {
    return "Player";
  }
  
  function getModelTable() {
    return "Player";
  }
  
  function isCachable() {
    return false;
  }
  
  function getCacheValidTime() {
    return -1;
  }

  /**
   * @return Player
   */
  function getNullObject() {
    $o = new Player(-1, null, null, null, null);
    return $o;
  }

  /**
   * @param string $pk
   * @param array $dict
   * @return Player
   */
  function createObjectFromDict($pk, $dict) {
    $o = new Player($pk, $dict['playerName'], $dict['email'], $dict['affiliateKey'], $dict['affiliatedBy']);
    return $o;
  }

  /**
   * @param array $options
   * @param bool $single
   * @return Player|Player[]
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
      return Util::cast(parent::filter($options, $single), Player::class);
    }
    $objects = parent::filter($options, $single);
    if($join){
      return $objects;
    }
    $models = array();
    foreach($objects as $object){
      $models[] = Util::cast($object, Player::class);
    }
    return $models;
  }

  /**
   * @param string $pk
   * @return Player
   */
  function get($pk) {
    return Util::cast(parent::get($pk), Player::class);
  }

  /**
   * @param Player $model
   * @return Player
   */
  function save($model) {
    return Util::cast(parent::save($model), Player::class);
  }
}