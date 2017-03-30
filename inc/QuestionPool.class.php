<?php
use DBA\AnswerSession;

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 30.03.17
 * Time: 11:52
 */
class QuestionPool {
  private $pool = array();
  
  public function __construct() {
    // TODO: create pool
  }
  
  /**
   * @param $answerSession AnswerSession
   * @return SessionQuestion[]
   */
  public function getNextQuestionBlock($answerSession) {
    return array();
  }
}