<?php

/** @var $OBJECTS array */

use DBA\QueryFilter;
use DBA\Oauth;

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

$qF = new QueryFilter(Oauth::PLAYER_ID, $OAUTH->getPlayer()->getId(), "=");
$providers = $FACTORIES::getOauthFactory()->filter(array($FACTORIES::FILTER => $qF));
$activated = new DataSet();
foreach ($providers as $provider) {
  $activated->addValue($provider->getType(), true);
}
$OBJECTS['activated'] = $activated;

echo $TEMPLATE->render($OBJECTS);