<?php

/** @var $OBJECTS array */
/** @var $OAUTH OAuthLogin */

require_once(dirname(__FILE__) . "/inc/load.php");

if (!$OAUTH->isLoggedin() && !isset($_GET['id'])) {
  header("Location: index.php?err=4" . time());
  die();
}

$MENU->setActive("profile");
$OBJECTS['pageTitle'] = "Profile";
$TEMPLATE = new Template("content/profile");

$player = null;
if ($OAUTH->isLoggedin() && !isset($_GET['id'])) {
  $player = $OAUTH->getPlayer();
}
else {
  $player = $FACTORIES::getPlayerFactory()->get($_GET['id']);
  if ($player == null) {
    UI::printError("ERROR", "Invalid User!");
  }
}
$OBJECTS['player'];

echo $TEMPLATE->render($OBJECTS);