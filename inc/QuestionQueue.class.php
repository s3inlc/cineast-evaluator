<?php
/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 30.03.17
 * Time: 16:46
 */

class QuestionQueue {
  private $questions;
  
  public function __construct($questions) {
    $this->questions = $questions;
  }
  
  /**
   * @return SessionQuestion
   */
  public function getFirst(){
    foreach($this->questions as $question){
      return $question;
    }
    return null;
  }
  
  public function pop(){
    foreach($this->questions as $key => $question){
      unset($this->questions[$key]);
      return;
    }
  }
  
  /**
   * @return SessionQuestion[]
   */
  public function getQuestions(){
    return $this->questions;
  }
  
  /**
   * @return bool
   */
  public function questionAvailable(){
    return sizeof($this->questions) > 0;
  }
}