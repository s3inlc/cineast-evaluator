<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 02.01.17
 * Time: 23:57
 */

namespace DBA;

class GameFactory extends AbstractModelFactory {
  function getModelName() {
    return "Game";
  }
  
  function getModelTable() {
    return "Game";
  }
  
  function isCachable() {
    return false;
  }
  
  function getCacheValidTime() {
    return -1;
  }

  /**
   * @return Game
   */
  function getNullObject() {
    $o = new Game(-1, null, null, null, null, null);
    return $o;
  }

  /**
   * @param string $pk
   * @param array $dict
   * @return Game
   */
  function createObjectFromDict($pk, $dict) {
    $o = new Game($pk, $dict['playerId'], $dict['answerSessionId'], $dict['finishedTime'], $dict['gameScore'], $dict['fullScore']);
    return $o;
  }

  /**
   * @param array $options
   * @param bool $single
   * @return Game|Game[]
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
      return Util::cast(parent::filter($options, $single), Game::class);
    }
    $objects = parent::filter($options, $single);
    if($join){
      return $objects;
    }
    $models = array();
    foreach($objects as $object){
      $models[] = Util::cast($object, Game::class);
    }
    return $models;
  }

  /**
   * @param string $pk
   * @return Game
   */
  function get($pk) {
    return Util::cast(parent::get($pk), Game::class);
  }

  /**
   * @param Game $model
   * @return Game
   */
  function save($model) {
    return Util::cast(parent::save($model), Game::class);
  }
}