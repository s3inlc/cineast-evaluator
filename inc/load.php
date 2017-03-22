<?php

use DBA\Factory;

//set to 1 for debugging
ini_set("display_errors", "0");

$OBJECTS = array();

$VERSION = "0.1.0";
$OBJECTS['version'] = $VERSION;

// setting some constants
define("STORAGE_PATH", "/var/www/evaluator/");

// include all .class.php files in inc dir
$dir = scandir(dirname(__FILE__));
foreach ($dir as $entry) {
  if (strpos($entry, ".class.php") !== false) {
    require_once(dirname(__FILE__) . "/" . $entry);
  }
}

// include DBA
require_once(dirname(__FILE__) . "/../dba/init.php");

// include Template
require_once(dirname(__FILE__) . "/../templating/init.php");

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

