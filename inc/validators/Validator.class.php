<?php

abstract class Validator {
  abstract function validateRunning($answerSession, $validity);
  
  abstract function validateFinished($answerSession, $validity);
}