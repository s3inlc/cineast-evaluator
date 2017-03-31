<?php
/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 31.03.17
 * Time: 12:28
 */

abstract class Validator {
  abstract function validateRunning($answerSession, $validity);
  abstract function validateFinished($answerSession, $validity);
}