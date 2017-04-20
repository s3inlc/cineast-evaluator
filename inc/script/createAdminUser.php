<?php

/*
 * Use this script to add admin users (especially the first one)
 */

use DBA\QueryFilter;
use DBA\User;

require_once(dirname(__FILE__) . "/../load.php");

// set username and email here
$username = "admin";
$email = "admin@example.org";

$qF = new QueryFilter(User::USERNAME, $username, "=");
$user = $FACTORIES::getUserFactory()->filter(array($FACTORIES::FILTER => $qF), true);
if ($user != null) {
  die("This username is already used!\n");
}

$salt = Util::randomString(50);
$pass = Util::randomString(10);
$hash = Encryption::passwordHash($pass, $salt);

$user = new User(0, $username, $email, $hash, $salt, 1, 1, 0, time(), 600);
$user = $FACTORIES::getUserFactory()->save($user);

echo "User $username with ID " . $user->getId() . " created successfully!\n";
echo "Password: $pass\n";