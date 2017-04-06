<?php
/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 05.04.17
 * Time: 17:04
 */

require_once(dirname(__FILE__) . "/../load.php");

/** @var $VALIDATORS Validator[] */

$session = $FACTORIES::getAnswerSessionFactory()->get($argv[1]);
$validator = new MultivariantCrowdValidator();
$currentValidity = $validator->validateFinished($session, 0);
$validator = new PatternValidator();
$currentValidity = $validator->validateFinished($session, $currentValidity);
$validator = new TimeValidator();
$currentValidity = $validator->validateFinished($session, $currentValidity);
echo $currentValidity . "\n";
  




