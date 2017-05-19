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

$hits = $client->listHITs();

print_r($hits);


