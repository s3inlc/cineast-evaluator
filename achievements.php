<?php

/** @var $OBJECTS array */

use DBA\Achievement;
use DBA\QueryFilter;

require_once(dirname(__FILE__) . "/inc/load.php");

if (!$OAUTH->isLoggedin()) {
  header("Location: index.php?err=4" . time());
  die();
}

$MENU->setActive("achievements");
$OBJECTS['pageTitle'] = "Achievements";
$TEMPLATE = new Template("content/achievements");

// get all achievements of this user
$qF = new QueryFilter(Achievement::PLAYER_ID, $OAUTH->getPlayer()->getId(), "=");
$achievements = $FACTORIES::getAchievementFactory()->filter(array($FACTORIES::FILTER => $qF));
$achieved = new DataSet();
foreach ($achievements as $achievement) {
  $achieved->addValue($achievement->getAchievementName(), true);
}
$OBJECTS['achieved'] = $achieved;

$achievementTester = new AchievementTester();
$OBJECTS['achievements'] = $achievementTester->getAllAchievemens();

echo $TEMPLATE->render($OBJECTS);