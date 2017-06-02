<?php
/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 19.05.17
 * Time: 11:04
 */

use DBA\AnswerSession;
use DBA\Microworker;
use DBA\QueryFilter;

require_once(dirname(__FILE__) . "/../load.php");

// conntect to API
$credentials = json_decode(file_get_contents(dirname(__FILE__) . "/../mturk_api_key.json"), true);
$client = new \Aws\MTurk\MTurkClient(array(
    "version" => "latest",
    "region" => "us-east-1",
    "endpoint" => "https://mturk-requester.us-east-1.amazonaws.com",
    "credentials" => $credentials
  )
);

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
    preg_match('/\?token\=([a-zA-Z0-9]*?)\"/', $hit['Question'], $matches);
    if (sizeof($matches) < 1) {
      echo "Invalid HIT!\n";
      continue;
    }
    $token = $matches[1];
    echo "\n  check token $token... ";
    
    $qF = new QueryFilter(Microworker::TOKEN, $token, "=");
    $microworker = $FACTORIES::getMicroworkerFactory()->filter(array($FACTORIES::FILTER => $qF), true);
    if ($microworker->getTimeClosed() == 0) {
      echo "Not finished yet\n";
      continue;
    }
    else if ($microworker->getIsConfirmed() != 0) {
      echo "Already confirmed/rejected\n";
      continue;
    }
    else if ($microworker->getIsLocked() == 1) {
      echo "Is locked\n";
      continue;
    }
    
    echo "Getting Assignments...";
    
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
      echo " " . sizeof($assignments) . "\n";
      foreach ($assignments as $assignment) {
        $matches = array();
        preg_match('/\<FreeText\>(.*?)\<\/FreeText\>/', $assignment['Answer'], $matches);
        $answer = trim($matches[1]);
        echo "  Assignment " . $assignment['AssignmentId'] . " by " . $assignment['WorkerId'] . ": " . $answer . "\n";
        
        // check here if the code was correctly
        if ($microworker->getSurveyCode() != $answer) {
          // user entered wrong code
          $client->rejectAssignment(array(
              "AssignmentId" => $assignment['AssignmentId'],
              "RequesterFeedback" => "Invalid survey code was entered."
            )
          );
          $microworker->setIsConfirmed(-1);
        }
        else {
          // check if he got enough validity
          $qF = new QueryFilter(AnswerSession::MICROWORKER_ID, $microworker->getId(), "=");
          $answerSessions = $FACTORIES::getAnswerSessionFactory()->filter(array($FACTORIES::FILTER => $qF));
          // take the completed one (normally there should not be more than one, but just in case
          $session = null;
          foreach ($answerSessions as $answerSession) {
            if ($answerSession->getIsOpen() == 0) {
              $session = $answerSession;
              break;
            }
          }
          if ($session == null) {
            // for some reason the user had a correct survey code but did not complete any session
            $client->rejectAssignment(array(
                "AssignmentId" => $assignment['AssignmentId'],
                "RequesterFeedback" => "No data found for survey code."
              )
            );
            $microworker->setIsConfirmed(-1);
          }
          else {
            if ($session->getCurrentValidity() >= MICROWORKER_VALIDITY_CONFIRM_LIMIT) {
              $client->approveAssignment(array(
                  "AssignmentId" => $assignment['AssignmentId'],
                  "RequesterFeedback" => "Well done :)"
                )
              );
              $microworker->setIsConfirmed(1);
              // TODO: maybe we can here give bonuses later
            }
            else {
              $client->rejectAssignment(array(
                  "AssignmentId" => $assignment['AssignmentId'],
                  "RequesterFeedback" => "You did not pass the checks to detect not correctly answering workers."
                )
              );
              $microworker->setIsConfirmed(-1);
            }
          }
        }
        $microworker->setWorkerId($assignment['WorkerId']);
        $FACTORIES::getMicroworkerFactory()->update($microworker);
      }
    }
  }
} while (sizeof($hits) > 0);