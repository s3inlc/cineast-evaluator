<?php

require_once(dirname(__FILE__) . "/../load.php");

/** @var $VALIDATORS Validator[] */

/*
 * IMPORTANT:
 * This script is only for experimental purposes and should not be called regularly
 */

$session = $FACTORIES::getAnswerSessionFactory()->get($argv[1]);
$validator = new MultivariantCrowdValidator();
$currentValidity = $validator->validateFinished($session, 0);
$validator = new PatternValidator();
$currentValidity = $validator->validateFinished($session, $currentValidity);
$validator = new TimeValidator();
$currentValidity = $validator->validateFinished($session, $currentValidity);
echo $currentValidity . "\n";
  




