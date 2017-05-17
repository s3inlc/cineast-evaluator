<?php

require_once(dirname(__FILE__) . "/inc/load.php");

$MTURK = new MTurk();

if($MTURK->isMechanicalTurk()){

}
else{
  // start a new microworker
}



$hitId        = $_REQUEST["hitId"];
$assignmentId = $_REQUEST["assignmentId"];
$workerId     = $_REQUEST["workerId"];

echo "Hit ID: $hitId\n";
echo "Ass ID: $assignmentId\n";
echo "Worker ID: $workerId\n";
?>