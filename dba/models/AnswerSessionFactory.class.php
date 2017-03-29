<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 02.01.17
 * Time: 23:57
 */

namespace DBA;

class AnswerSessionFactory extends AbstractModelFactory {
  function getModelName() {
    return "AnswerSession";
  }
  
  function getModelTable() {
    return "AnswerSession";
  }
  
  function isCachable() {
    return false;
  }
  
  function getCacheValidTime() {
    return -1;
  }

  /**
   * @return AnswerSession
   */
  function getNullObject() {
    $o = new AnswerSession(-1, null, null, null, null, null, null, null, null);
    return $o;
  }

  /**
   * @param string $pk
   * @param array $dict
   * @return AnswerSession
   */
  function createObjectFromDict($pk, $dict) {
    $o = new AnswerSession($pk, $dict['microworkerId'], $dict['userId'], $dict['playerId'], $dict['currentValidity'], $dict['isOpen'], $dict['timeOpened'], $dict['userAgentIp'], $dict['userAgentHeader']);
    return $o;
  }

  /**
   * @param array $options
   * @param bool $single
   * @return AnswerSession|AnswerSession[]
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
      return Util::cast(parent::filter($options, $single), AnswerSession::class);
    }
    $objects = parent::filter($options, $single);
    if($join){
      return $objects;
    }
    $models = array();
    foreach($objects as $object){
      $models[] = Util::cast($object, AnswerSession::class);
    }
    return $models;
  }

  /**
   * @param string $pk
   * @return AnswerSession
   */
  function get($pk) {
    return Util::cast(parent::get($pk), AnswerSession::class);
  }

  /**
   * @param AnswerSession $model
   * @return AnswerSession
   */
  function save($model) {
    return Util::cast(parent::save($model), AnswerSession::class);
  }
}