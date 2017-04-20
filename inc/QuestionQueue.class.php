<?php

class QuestionQueue {
  private $questions;
  
  public function __construct($questions) {
    $this->questions = array();
    $index = 0;
    foreach ($questions as $question) {
      $this->questions[$index] = $question;
      $index++;
    }
  }
  
  /**
   * @return SessionQuestion
   */
  public function getFirst() {
    if (sizeof($this->questions) == 0) {
      return null;
    }
    return $this->questions[0];
  }
  
  public function pop() {
    if (sizeof($this->questions) == 1) {
      unset($this->questions[0]);
      return;
    }
    for ($i = 0; $i < sizeof($this->questions) - 1; $i++) {
      $this->questions[$i] = $this->questions[$i + 1];
    }
    unset($this->questions[sizeof($this->questions) - 1]);
  }
  
  /**
   * @param $question SessionQuestion
   */
  public function prependQuestion($question) {
    for ($i = sizeof($this->questions); $i > 0; $i--) {
      $this->questions[$i] = $this->questions[$i - 1];
    }
    $this->questions[0] = $question;
  }
  
  /**
   * @return SessionQuestion[]
   */
  public function getQuestions() {
    return $this->questions;
  }
  
  /**
   * @return bool
   */
  public function questionAvailable() {
    return sizeof($this->questions) > 0;
  }
}