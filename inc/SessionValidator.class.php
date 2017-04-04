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
  public function update($errorType){
    /** @var $VALIDATORS Validator[] */
    global $VALIDATORS;
    
    $currentValidity = $this->answerSession->getCurrentValidity();
    foreach($VALIDATORS as $validator){
      if($this->answerSession->getIsOpen() == 1){
        $currentValidity = $validator->validateRunning($this->answerSession, $currentValidity);
      }
      else{
        $currentValidity = $validator->validateFinished($this->answerSession, $currentValidity);
      }
    }
    
    if ($errorType != ErrorType::NO_ERROR) {
      $currentValidity -= SessionValidator::MALUS_ANSWER_ERROR;
    }
    
    return $currentValidity;
  }
}