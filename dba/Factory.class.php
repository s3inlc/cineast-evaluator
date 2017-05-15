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
  private static $oauthFactory = null;
  private static $threeCompareAnswerFactory = null;
  private static $twoCompareAnswerFactory = null;
  private static $validationFactory = null;
  private static $gameFactory = null;
  private static $achievementFactory = null;

  public static function getUserFactory() {
    if (self::$userFactory == null) {
      self::$userFactory = new UserFactory();
    }
    return self::$userFactory;
  }
  
  public static function getSessionFactory() {
    if (self::$sessionFactory == null) {
      self::$sessionFactory = new SessionFactory();
    }
    return self::$sessionFactory;
  }
  
  public static function getQueryFactory() {
    if (self::$queryFactory == null) {
      self::$queryFactory = new QueryFactory();
    }
    return self::$queryFactory;
  }
  
  public static function getResultTupleFactory() {
    if (self::$resultTupleFactory == null) {
      self::$resultTupleFactory = new ResultTupleFactory();
    }
    return self::$resultTupleFactory;
  }
  
  public static function getQueryResultTupleFactory() {
    if (self::$queryResultTupleFactory == null) {
      self::$queryResultTupleFactory = new QueryResultTupleFactory();
    }
    return self::$queryResultTupleFactory;
  }
  
  public static function getMediaObjectFactory() {
    if (self::$mediaObjectFactory == null) {
      self::$mediaObjectFactory = new MediaObjectFactory();
    }
    return self::$mediaObjectFactory;
  }
  
  public static function getMediaTypeFactory() {
    if (self::$mediaTypeFactory == null) {
      self::$mediaTypeFactory = new MediaTypeFactory();
    }
    return self::$mediaTypeFactory;
  }
  
  public static function getAnswerSessionFactory() {
    if (self::$answerSessionFactory == null) {
      self::$answerSessionFactory = new AnswerSessionFactory();
    }
    return self::$answerSessionFactory;
  }
  
  public static function getPlayerFactory() {
    if (self::$playerFactory == null) {
      self::$playerFactory = new PlayerFactory();
    }
    return self::$playerFactory;
  }
  
  public static function getOauthFactory() {
    if (self::$oauthFactory == null) {
      self::$oauthFactory = new OauthFactory();
    }
    return self::$oauthFactory;
  }
  
  public static function getThreeCompareAnswerFactory() {
    if (self::$threeCompareAnswerFactory == null) {
      self::$threeCompareAnswerFactory = new ThreeCompareAnswerFactory();
    }
    return self::$threeCompareAnswerFactory;
  }
  
  public static function getTwoCompareAnswerFactory() {
    if (self::$twoCompareAnswerFactory == null) {
      self::$twoCompareAnswerFactory = new TwoCompareAnswerFactory();
    }
    return self::$twoCompareAnswerFactory;
  }
  
  public static function getValidationFactory() {
    if (self::$validationFactory == null) {
      self::$validationFactory = new ValidationFactory();
    }
    return self::$validationFactory;
  }
  
  public static function getGameFactory() {
    if (self::$gameFactory == null) {
      self::$gameFactory = new GameFactory();
    }
    return self::$gameFactory;
  }
  
  public static function getAchievementFactory() {
    if (self::$achievementFactory == null) {
      self::$achievementFactory = new AchievementFactory();
    }
    return self::$achievementFactory;
  }

  const FILTER = "filter";
  const JOIN = "join";
  const ORDER = "order";
  const UPDATE = "update";
}
