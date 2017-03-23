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

if(isset($_POST['action'])){
  // handle actions
}

echo $TEMPLATE->render($OBJECTS);