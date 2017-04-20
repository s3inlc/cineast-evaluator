<?php
use DBA\MediaObject;
use DBA\ResultTuple;

class SessionQuestion {
  const TYPE_UNDEFINED      = "undefined";
  const TYPE_COMPARE_TWO    = "compare2";
  const TYPE_COMPARE_TRIPLE = "compare3";
  
  private $type         = SessionQuestion::TYPE_UNDEFINED;
  private $mediaObjects = array();
  private $resultTuples = array();
  
  /**
   * SessionQuestion constructor.
   * @param $questionType string
   * @param $mediaObjects MediaObject[]
   */
  public function __construct($questionType, $mediaObjects, $resultTuples) {
    $this->type = $questionType;
    $this->mediaObjects = $mediaObjects;
    $this->resultTuples = $resultTuples;
  }
  
  /**
   * @return string
   */
  public function getQuestionType() {
    return $this->type;
  }
  
  /**
   * @return MediaObject[]
   */
  public function getMediaObjects() {
    return $this->mediaObjects;
  }
  
  /**
   * @return ResultTuple[]
   */
  public function getResultTuples() {
    return $this->resultTuples;
  }
}