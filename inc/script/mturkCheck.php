<?php
/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 19.05.17
 * Time: 11:04
 */

use DBA\Microworker;
use DBA\QueryFilter;

require_once(dirname(__FILE__) . "/../load.php");

// conntect to API
$credentials = json_decode(file_get_contents(dirname(__FILE__) . "/../mturk_api_key.json"), true);
$client = new \Aws\MTurk\MTurkClient(array(
    "version" => "latest",
    "region" => "us-east-1",
    "endpoint" => "https://mturk-requester-sandbox.us-east-1.amazonaws.com",
    "credentials" => $credentials
  )
);

$client->listAs

$nextToken = null;
do {
  $opts = array();
  if ($nextToken != null) {
    $opts['NextToken'] = $nextToken;
  }
  $result = $client->listHITs($opts);
  $hits = $result->toArray()['HITs'];
  if (isset($result->toArray()['NextToken'])) {
    $nextToken = $result->toArray()['NextToken'];
  }
  foreach ($hits as $hit) {
    echo "Processing HIT " . $hit['HITId'] . " with status: " . $hit['HITStatus'] . "... ";
    
    $matches = array();
    preg_match('/\&token\=([a-zA-Z0-9]*?)\"/', $hit['Question'], $matches);
    $token = $matches[1];
    echo "\n  check token $token... ";
    
    $qF = new QueryFilter(Microworker::TOKEN, $token, "=");
    $microworker = $FACTORIES::getMicroworkerFactory()->filter(array($FACTORIES::FILTER => $qF), true);
    if ($microworker->getTimeClosed() == 0) {
      echo "Not started yet\n";
      continue;
    }
    else if ($microworker->getIsConfirmed() == 1) {
      echo "Already confirmed\n";
      continue;
    }
    else if ($microworker->getIsLocked() == 1) {
      echo "Is locked\n";
      continue;
    }
    
    $result = $client->listAssignmentsForHIT(array(
        "HITId" => $hit['HITId'],
        "AssignmentStatuses" => array("Submitted")
      )
    );
    $assignments = $result->toArray()['Assignments'];
    if (sizeof($assignments) == 0) {
      echo " EMPTY\n";
    }
    else {
      echo " " . sizeof($assignments) . " Assignments:\n";
      foreach ($assignments as $assignment) {
        $matches = array();
        preg_match('/\<FreeText\>(.*?)\<\/FreeText\>/', $assignment['Answer'], $matches);
        $answer = $matches[1];
        echo "  " . $assignment['AssignmentId'] . " by " . $assignment['WorkerId'] . ": " . $answer . "\n";
      }
    }
  }
} while (sizeof($hits) > 0);