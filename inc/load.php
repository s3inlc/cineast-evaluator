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
define("QUERIES_FOLDER", "queries/");

define("DEFAULT_SIMILARITY", "0");
define("DEFAULT_CERTAINTY", "0");
define("SESSION_SIZE", "20");

define("IMAGE_MAX_HEIGHT", 1500);
define("IMAGE_MAX_WIDTH", 1500);

define("SECURITY_QUESTION_THRESHOLD", 0.5);
define("SESSION_TIMEOUT", 3600*24); // set time limit after which a session is considered as closed even if not all questions are answered
define("GAUSS_LIMIT", 3); // defines the number of answers which is required to be able to put a gauss curve for a tuple
define("RESULT_TUPLE_EVALUATED_SIGMA_THRESHOLD", 0.3); // sigma of the result tuple has to be <= this value
define("RESULT_TUPLE_EVALUATED_ANSWERS_THRESHOLD", 10); // number of questions answered for this tuple has to >= this value

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

// include all validators
$VALIDATORS = array();
require_once(dirname(__FILE__)."/validators/Validator.class.php");
$dir = scandir(dirname(__FILE__)."/validators/");
foreach ($dir as $entry) {
  if (strpos($entry, ".class.php") !== false) {
    require_once(dirname(__FILE__) . "/validators/" . $entry);
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
$OBJECTS['administrator'] = false;
if ($LOGIN->isLoggedin()) {
  $OBJECTS['user'] = $LOGIN->getUser();
}

