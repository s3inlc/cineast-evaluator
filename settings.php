<?php

/** @var $OBJECTS array */

use DBA\Game;
use DBA\QueryFilter;

require_once(dirname(__FILE__) . "/inc/load.php");

if (!$OAUTH->isLoggedin()) {
  header("Location: index.php?err=4" . time());
  die();
}

$MENU->setActive("settings");
$OBJECTS['pageTitle'] = "Settings";
$TEMPLATE = new Template("content/settings");

if (isset($_POST['action'])) {
  $settingsHandler = new SettingsHandler();
  $settingsHandler->handle($_POST['action']);
}

$OBJECTS['player'] = $OAUTH->getPlayer();

echo $TEMPLATE->render($OBJECTS);