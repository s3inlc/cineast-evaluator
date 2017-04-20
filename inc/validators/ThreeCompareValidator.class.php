<?php

class ThreeCompareValidator extends Validator {
  
  function validateRunning($answerSession, $validity) {
    // TODO: Implement validateRunning() method.
    return $validity;
  }
  
  function validateFinished($answerSession, $validity) {
    // TODO: Implement validateFinished() method.
    return $validity;
  }
}

$VALIDATORS[] = new ThreeCompareValidator();