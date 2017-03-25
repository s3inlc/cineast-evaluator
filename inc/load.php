<?php

use DBA\Factory;

//set to 1 for debugging
ini_set("display_errors", "0");

$OBJECTS = array();

$VERSION = "0.1.0";
$OBJECTS['version'] = $VERSION;

// setting some constants
define("STORAGE_PATH", "/var/www/evaluator/");
define("TMP_FOLDER", "tmp/");
define("MEDIA_FOLDER", "media/");
define("QUERIES_FOLDER", "queries");

define("DEFAULT_SIMILARITY", "0");
define("DEFAULT_CERTAINTY", "0");

// include all .class.php files in inc dir
$dir = scandir(dirname(__FILE__));
foreach ($dir as $entry) {
  if (strpos($entry, ".class.php") !== false) {
    require_once(dirname(__FILE__) . "/" . $entry);
  }
}

// include all handlers
require_once(dirname(__FILE__)."/handlers/Handler.class.php");
$dir = scandir(dirname(__FILE__)."/handlers/");
foreach ($dir as $entry) {
  if (strpos($entry, ".class.php") !== false) {
    require_once(dirname(__FILE__) . "/handlers/" . $entry);
  }
}

// include DBA
require_once(dirname(__FILE__) . "/../dba/init.php");

// include Template
require_once(dirname(__FILE__) . "/../templating/init.php");

// check required folders
Util::checkFolders();

$FACTORIES = new Factory();

$LOGIN = null;
$MENU = new Menu();
$OBJECTS['menu'] = $MENU;
$OBJECTS['messages'] = array();
$LOGIN = new Login();
$OBJECTS['login'] = $LOGIN;
if ($LOGIN->isLoggedin()) {
  $OBJECTS['user'] = $LOGIN->getUser();
}

