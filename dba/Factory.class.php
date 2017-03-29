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
  private static $queryFactory = null;
  private static $resultTupleFactory = null;
  private static $queryResultTupleFactory = null;
  private static $mediaObjectFactory = null;
  private static $mediaTypeFactory = null;
  private static $answerSessionFactory = null;
  private static $playerFactory = null;
  private static $threeCompareAnswerFactory = null;
  private static $twoCompareAnswerFactory = null;

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
  
  public static function getQueryFactory() {
    if (self::$queryFactory == null) {
      $f = new QueryFactory();
      self::$queryFactory = $f;
      return $f;
    } else {
      return self::$queryFactory;
    }
  }
  
  public static function getResultTupleFactory() {
    if (self::$resultTupleFactory == null) {
      $f = new ResultTupleFactory();
      self::$resultTupleFactory = $f;
      return $f;
    } else {
      return self::$resultTupleFactory;
    }
  }
  
  public static function getQueryResultTupleFactory() {
    if (self::$queryResultTupleFactory == null) {
      $f = new QueryResultTupleFactory();
      self::$queryResultTupleFactory = $f;
      return $f;
    } else {
      return self::$queryResultTupleFactory;
    }
  }
  
  public static function getMediaObjectFactory() {
    if (self::$mediaObjectFactory == null) {
      $f = new MediaObjectFactory();
      self::$mediaObjectFactory = $f;
      return $f;
    } else {
      return self::$mediaObjectFactory;
    }
  }
  
  public static function getMediaTypeFactory() {
    if (self::$mediaTypeFactory == null) {
      $f = new MediaTypeFactory();
      self::$mediaTypeFactory = $f;
      return $f;
    } else {
      return self::$mediaTypeFactory;
    }
  }
  
  public static function getAnswerSessionFactory() {
    if (self::$answerSessionFactory == null) {
      $f = new AnswerSessionFactory();
      self::$answerSessionFactory = $f;
      return $f;
    } else {
      return self::$answerSessionFactory;
    }
  }
  
  public static function getPlayerFactory() {
    if (self::$playerFactory == null) {
      $f = new PlayerFactory();
      self::$playerFactory = $f;
      return $f;
    } else {
      return self::$playerFactory;
    }
  }
  
  public static function getThreeCompareAnswerFactory() {
    if (self::$threeCompareAnswerFactory == null) {
      $f = new ThreeCompareAnswerFactory();
      self::$threeCompareAnswerFactory = $f;
      return $f;
    } else {
      return self::$threeCompareAnswerFactory;
    }
  }
  
  public static function getTwoCompareAnswerFactory() {
    if (self::$twoCompareAnswerFactory == null) {
      $f = new TwoCompareAnswerFactory();
      self::$twoCompareAnswerFactory = $f;
      return $f;
    } else {
      return self::$twoCompareAnswerFactory;
    }
  }

  const FILTER = "filter";
  const JOIN = "join";
  const ORDER = "order";
  const UPDATE = "update";
}
