<?php

require_once(dirname(__FILE__) . "/inc/load.php");

/** @var Login $LOGIN */
/** @var array $OBJECTS */

if (!$LOGIN->isLoggedin()) {
  header("Location: admin.php");
  die();
}

$LOGIN->logout();
header("Location: admin.php?logout=1" . time());
