<?php
/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 31.03.17
 * Time: 12:35
 */

/**
 * Class TimeValidator
 *
 * This class will only validate sessions when the last question is finished
 */

class TimeValidator extends Validator {
  function validateRunning($answerSession, $validity) {
    return $validity;
  }
  
  function validateFinished($answerSession, $validity) {
    // TODO: Implement validateFinished() method.
  }
}