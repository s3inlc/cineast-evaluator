<?php
/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 19.05.17
 * Time: 11:04
 */

require_once(dirname(__FILE__) . "/../load.php");

// conntect to API
$client = new \Aws\MTurk\MTurkClient(array("version" => "latest", "region" => "us-east-1", "endpoint_url" => "https://mturk-requester-sandbox.us-east-1.amazonaws.com"));

$hits = $client->listHITs();

print_r($hits);


