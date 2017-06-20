<?php

use DBA\Factory;
use DBA\Player;
use DBA\QueryFilter;

//set to 1 for debugging
ini_set("display_errors", "0");

$OBJECTS = array();

define("GAME_NAME", "ArtSimily");
define("DOMAIN", "https://example.org");
$VERSION = "0.1.0";
$OBJECTS['GameName'] = GAME_NAME;
$OBJECTS['Domain'] = DOMAIN;
$OBJECTS['version'] = $VERSION;

// setting some constants
define("STORAGE_PATH", "/var/www/evaluator/"); // path where the media/query files are stored
define("TMP_FOLDER", "tmp/"); // folder where it extracts zips and stores temporary data
define("MEDIA_FOLDER", "media/"); // subfolder of the storage path where all media is saved
define("QUERIES_FOLDER", "queries/"); // subfolder of the storage path where the queries will be saved

define("NO_REPLY_EMAIL", "no_reply@artsimily.com");
define("DEFAULT_EMAIL_FROM", "ArtSimily");

define("DEFAULT_SIMILARITY", "0"); // TODO: should be removed
define("DEFAULT_CERTAINTY", "0"); // TODO: should be removed
define("SESSION_SIZE_GAME", 50); // number of questions for a session when playing a game (including the security questions)
define("SESSION_SIZE_MICROWORKER", 100); // number of questions for a session when using a microworker (including the security questions)
define("SESSION_SIZE_GAME_UNREGISTERED", 20); // number of questions for a session when playing a game not logged in (including the security questions)

// max size an image can have before it will be resized to these sizes
define("IMAGE_MAX_HEIGHT", 1000);
define("IMAGE_MAX_WIDTH", 1000);

define("SECURITY_QUESTION_THRESHOLD", 0.5);
define("SESSION_TIMEOUT", 3600 * 24); // set time limit after which a session is considered as closed even if not all questions are answered
define("GAUSS_LIMIT", 3); // defines the number of answers which is required to be able to put a gauss curve for a tuple
define("RESULT_TUPLE_EVALUATED_SIGMA_THRESHOLD", 0.3); // sigma of the result tuple has to be <= this value
define("RESULT_TUPLE_EVALUATED_ANSWERS_THRESHOLD", 3); // number of questions answered for this tuple has to >= this value
define("RESULT_TUPLE_EVALUATED_ANSWERS_LIMIT", 10);

define("MICROWORKER_VALIDITY_CONFIRM_LIMIT", 0.5); // this is the limit where a microworker gets rejected when his validity is below this value

// include all .class.php files in inc dir
$dir = scandir(dirname(__FILE__));
foreach ($dir as $entry) {
  if (strpos($entry, ".class.php") !== false) {
    require_once(dirname(__FILE__) . "/" . $entry);
  }
}

// include all handlers
require_once(dirname(__FILE__) . "/handlers/Handler.class.php");
$dir = scandir(dirname(__FILE__) . "/handlers/");
foreach ($dir as $entry) {
  if (strpos($entry, ".class.php") !== false) {
    require_once(dirname(__FILE__) . "/handlers/" . $entry);
  }
}

// include all validators
$VALIDATORS = array();
require_once(dirname(__FILE__) . "/validators/Validator.class.php");
$dir = scandir(dirname(__FILE__) . "/validators/");
foreach ($dir as $entry) {
  if (strpos($entry, ".class.php") !== false) {
    require_once(dirname(__FILE__) . "/validators/" . $entry);
  }
}

// include gamification parts
$dir = scandir(dirname(__FILE__) . "/gamification/");
foreach ($dir as $entry) {
  if (strpos($entry, ".class.php") !== false) {
    require_once(dirname(__FILE__) . "/gamification/" . $entry);
  }
}
require_once(dirname(__FILE__) . "/gamification/achievements/GameAchievement.class.php");
$dir = scandir(dirname(__FILE__) . "/gamification/achievements");
foreach ($dir as $entry) {
  if (strpos($entry, ".class.php") !== false) {
    require_once(dirname(__FILE__) . "/gamification/achievements/" . $entry);
  }
}

// include DBA
require_once(dirname(__FILE__) . "/../dba/init.php");

// include Template
require_once(dirname(__FILE__) . "/../templating/init.php");

// load OAuth stuff
require_once(__DIR__ . '/../vendor/autoload.php');

session_start();

// check required folders
Util::checkFolders();

$FACTORIES = new Factory();

$MENU = new Menu();
$OBJECTS['menu'] = $MENU;
$DEBUG = array();
$OBJECTS['messages'] = array();
$LOGIN = new Login();
if (!isset($OVERRIDELOGIN) || !$OVERRIDELOGIN) {
  $OAUTH = new OAuthLogin();
  $OBJECTS['oauth'] = $OAUTH;
  
  // handle if user accessed this page with an affiliate link
  if (!$OAUTH->isLoggedin() && isset($_GET['affiliate']) && !isset($_SESSION['affiliate'])) {
    $affiliate = $_GET['affiliate'];
    $qF = new QueryFilter(Player::AFFILIATE_KEY, $affiliate, "=");
    $check = $FACTORIES::getPlayerFactory()->filter(array($FACTORIES::FILTER => $qF), true);
    if ($check != null) {
      $_SESSION['affiliate'] = $affiliate;
    }
  }
}
$OBJECTS['login'] = $LOGIN;
$OBJECTS['administrator'] = false;
if ($LOGIN->isLoggedin()) {
  $OBJECTS['user'] = $LOGIN->getUser();
}


