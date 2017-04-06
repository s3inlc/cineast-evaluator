<?php
use DBA\AnswerSession;

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 30.03.17
 * Time: 16:30
 */
class SessionValidator {
  private $answerSession;
  
  const MALUS_ANSWER_ERROR = 0.1;
  
  /**
   * SessionValidator constructor.
   * @param $answerSession AnswerSession
   */
  public function __construct($answerSession) {
    // TODO: load some session info here
    $this->answerSession = $answerSession;
  }
  
  /**
   * @param $errorType string
   * @return float the new updated validity of the session
   */
  public function update($errorType) {
    /** @var $VALIDATORS Validator[] */
    //global $VALIDATORS;
    
    $gaussValidator = new MultivariantCrowdValidator();
    $patternValidator = new PatternValidator();
    $timeValidator = new TimeValidator();
    
    if ($this->answerSession->getIsOpen() == 1) {
      $currentValidity = $gaussValidator->validateRunning($this->answerSession, 0);
      $currentValidity = $patternValidator->validateRunning($this->answerSession, $currentValidity);
      $currentValidity = $timeValidator->validateRunning($this->answerSession, $currentValidity);
    }
    else {
      $currentValidity = $gaussValidator->validateFinished($this->answerSession, 0);
      $currentValidity = $patternValidator->validateFinished($this->answerSession, $currentValidity);
      $currentValidity = $timeValidator->validateFinished($this->answerSession, $currentValidity);
    }
    
    /*foreach($VALIDATORS as $validator){
      if($this->answerSession->getIsOpen() == 1){
        $currentValidity = $validator->validateRunning($this->answerSession, $currentValidity);
      }
      else{
        $currentValidity = $validator->validateFinished($this->answerSession, $currentValidity);
      }
    }*/
    
    if ($errorType != ErrorType::NO_ERROR) {
      $currentValidity -= SessionValidator::MALUS_ANSWER_ERROR;
    }
    if ($this->answerSession->getUserId() != null) {
      $currentValidity = 1; // if the user is an admin we override the result
    }
    
    return $currentValidity;
  }
}