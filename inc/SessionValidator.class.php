<?php
use DBA\AnswerSession;

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 30.03.17
 * Time: 16:30
 */

class SessionValidator {
  /**
   * SessionValidator constructor.
   * @param $answerSession AnswerSession
   */
  public function __construct($answerSession) {
    // TODO: load some session info here
  }
  
  /**
   * @param $errorType string
   * @return float the new updated validity of the session
   */
  public function update($errorType){
    // TODO: update the validity of the session based on the error type and all the available answers
    return 0.5;
  }
}