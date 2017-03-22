<?php

define("TEMPLATES_PATH", dirname(__FILE__) . "/exampleTemplates/");
define("LANGUAGES_PATH", dirname(__FILE__) . "/lang/");

require_once(dirname(__FILE__) . "/../templating/Statement.class.php");
require_once(dirname(__FILE__) . "/../templating/Template.class.php");

$entries = scandir(dirname(__FILE__));
foreach ($entries as $entry) {
  if (strpos($entry, ".class.php") !== false) {
    require_once(dirname(__FILE__) . "/" . $entry);
  }
}

$LANGUAGE = new Lang();

$OBJECTS = array();
$OBJECTS['TEMPLATE_VERSION'] = "1.0.0";
