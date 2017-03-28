<?php
require_once(dirname(__FILE__) . "/inc/load.php");

/** @var Login $LOGIN */
/** @var array $OBJECTS */

if (!isset($_POST['username']) || !isset($_POST['password'])) {
  header("Location: admin.php?err=1" . time());
  die();
}

$username = $_POST['username'];
$password = $_POST['password'];

if (strlen($username) == 0 || strlen($password) == 0) {
  header("Location: admin.php?err=2" . time());
  die();
}

$LOGIN->login($username, $password);
if ($LOGIN->isLoggedin()) {
  header("Location: admin.php");
  die();
}

header("Location: admin.php?err=3" . time());
