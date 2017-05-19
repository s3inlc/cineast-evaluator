<?php
/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 19.05.17
 * Time: 11:04
 */

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

$nextToken = null;
do {
  $opts = array();
  if ($nextToken != null) {
    $opts['nextToken'] = $nextToken;
  }
  $result = $client->listHITs($opts);
  $hits = $result->toArray()['HITs'];
  $nextToken = $result->toArray()['nextToken'];
  foreach ($hits as $hit) {
    echo "Processing HIT " . $hit['HITId'];
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
        preg_match('/\<FreeText\>(.*?)\<\/FreeText\>/', $assignment['Answer'], $matches);
        $answer = $matches[1];
        echo "  " . $assignment['AssignmentId'] . " by " . $assignment['WorkerId'] . ": " . $answer . "\n";
      }
    }
  }
} while (sizeof($hits) > 0);