<?php

/**
 * This script can be used to import queries which are located in a folder (which needs to be provided as command line argument).
 *
 * Files starting with . will be ignored.
 * The name of the zip folder will be taken as query name.
 * Files which get imported will be deleted on successful import!
 */

/*
 * Speed for importing example:
 * Successfully imported 312 queries, required 6863 seconds
 */

use DBA\QueryFilter;
use DBA\User;

require_once(dirname(__FILE__) . "/../load.php");

if (!isset($argv[1])) {
  die("You need to provide a folder where the import zips are located!\n");
}
else if (!is_dir($argv[1])) {
  die("Provided path does not exist or is not a folder!\n");
}
else if (!isset($argv[2])) {
  die("You need to provide an admin username as which the queries are imported!\n");
}

$username = $argv[2];
$qF = new QueryFilter(User::USERNAME, $username, "=");
$user = $FACTORIES::getUserFactory()->filter(array($FACTORIES::FILTER => $qF), true);
if ($user == null) {
  die("Username is not valid!\n");
}
$LOGIN = @new Login();
$LOGIN->overrideUser($user);

$count = 0;
$startTime = time();

$folder = $argv[1];
$entries = scandir($argv[1]);
$queryHandler = new QueryHandler();
foreach ($entries as $entry) {
  if (strlen($entry) == 0 || $entry[0] == ".") {
    continue;
  }
  $path = $folder . "/" . $entry;
  if (Util::getExtension($path) != "zip" && !is_dir($path)) {
    continue; // skip all files which are not zip archives or folders
  }
  $queryName = str_replace(".zip", "", $entry);
  echo "Importing query '$queryName'...\n";
  
  // fake a file upload here
  $FILE = array(
    "tmp_name" => $path,
    "error" => 0,
    "name" => $entry
  );
  $OBJECTS = array();
  $queryHandler->addQuery($FILE, $queryName, false);
  if (UI::getNumMessages() > 0) {
    print_r($OBJECTS);
  }
  else {
    echo "OK\n";
    $count++;
  }
}

echo "Successfully imported $count queries, required " . (time() - $startTime) . " seconds\n";





