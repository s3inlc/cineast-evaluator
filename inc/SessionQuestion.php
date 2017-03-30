<?php
use DBA\MediaObject;

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 30.03.17
 * Time: 14:09
 */
class SessionQuestion {
  const TYPE_UNDEFINED      = "undefined";
  const TYPE_COMPARE_TWO    = "compare2";
  const TYPE_COMPARE_TRIPLE = "compare3";
  const TYPE_TWO_CHECK      = "check2";
  const TYPE_TRIPLE_CHECK   = "check3";
  
  private $type         = SessionQuestion::TYPE_UNDEFINED;
  private $mediaObjects = array();
  
  /**
   * SessionQuestion constructor.
   * @param $questionType string
   * @param $mediaObjects MediaObject[]
   */
  public function __construct($questionType, $mediaObjects) {
    $this->type = $questionType;
    $this->mediaObjects = $mediaObjects;
  }
  
  /**
   * @return string
   */
  public function getQuestionType(){
    return $this->type;
  }
  
  /**
   * @return MediaObject[]
   */
  public function getMediaObjects(){
    return $this->mediaObjects;
  }
}