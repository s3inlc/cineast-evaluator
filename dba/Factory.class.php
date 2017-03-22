<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 02.01.17
 * Time: 23:57
 */

namespace DBA;

class Factory {
  private static $userFactory = null;
  private static $sessionFactory = null;

  public static function getUserFactory() {
    if (self::$userFactory == null) {
      $f = new UserFactory();
      self::$userFactory = $f;
      return $f;
    } else {
      return self::$userFactory;
    }
  }
  
  public static function getSessionFactory() {
    if (self::$sessionFactory == null) {
      $f = new SessionFactory();
      self::$sessionFactory = $f;
      return $f;
    } else {
      return self::$sessionFactory;
    }
  }

  const FILTER = "filter";
  const JOIN = "join";
  const ORDER = "order";
  const UPDATE = "update";
}
