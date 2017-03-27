<?php

/** @var Login $LOGIN */
/** @var array $OBJECTS */

require_once(dirname(__FILE__) . "/inc/load.php");
if (!$LOGIN->isLoggedin()) {
  header("Location: index.php?err=4" . time());
  die();
}
$TEMPLATE = new Template("content/account");

$MENU->setActive("account");
$OBJECTS['pageTitle'] = "Cineast Evaluator";
$OBJECTS['administrator'] = true;

if(isset($_POST['action'])){
  $accountHandler = new AccountHandler();
  $accountHandler->handle($_POST['action']);
}

echo $TEMPLATE->render($OBJECTS);