<?php
/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 19.04.17
 * Time: 15:12
 */

/**
 * This script can be used to import queries which are located in a folder (which needs to be provided as command line argument).
 *
 * Files starting with . will be ignored.
 * The name of the zip folder will be taken as query name.
 * Files which get imported will be deleted on successful import!
 */

require_once(dirname(__FILE__) . "/../load.php");

if (!isset($argv[1])) {
  die("You need to provide a folder where the import zips are located!\n");
}
else if (!is_dir($argv[1])) {
  die("Provided path does not exist or is not a folder!\n");
}

$folder = $argv[1];
$entries = scandir($argv[1]);
$queryHandler = new QueryHandler();
foreach ($entries as $entry) {
  if (strlen($entry) == 0 || $entry[0] == ".") {
    continue;
  }
  $path = $folder . "/" . $entry;
  if (Util::getExtension($path) != "zip") {
    continue; // skip all files which are not zip archives
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
  if(UI::getNumMessages() > 0){
    print_r($OBJECTS);
  }
  else{
    echo "OK\n";
  }
}







