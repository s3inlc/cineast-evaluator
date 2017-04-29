<?php

require_once(dirname(__FILE__) . "/inc/load.php");

/** @var Login $LOGIN */
/** @var array $OBJECTS */

if (!$LOGIN->isLoggedin() && !$OAUTH->isLoggedin()) {
  header("Location: index.php");
}

if ($OAUTH->isLoggedin()) {
  $OAUTH->logout();
  header("Location: index.php");
  
}
if ($LOGIN->isLoggedin()) {
  $LOGIN->logout();
  header("Location: admin.php?logout=1" . time());
}
