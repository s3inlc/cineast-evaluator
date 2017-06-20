<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 20.06.17
 * Time: 13:27
 */
class Phrases {
  const PHRASES_FILE = "phrases.json";
  
  const PHRASES_START  = "startPhrases";
  const PHRASES_NORMAL = "phrases";
  
  private $phrases = array();
  
  function __construct() {
    $this->phrases = json_decode(file_get_contents(dirname(__FILE__) . "/" . Phrases::PHRASES_FILE), true);
  }
  
  public function getStartPhrase() {
    $size = sizeof($this->phrases[Phrases::PHRASES_START]);
    return $this->phrases[Phrases::PHRASES_START][mt_rand(0, $size - 1)];
  }
  
  public function getPhrase() {
    $size = sizeof($this->phrases[Phrases::PHRASES_NORMAL]);
    return $this->phrases[Phrases::PHRASES_NORMAL][mt_rand(0, $size - 1)];
  }
}