<?php

/** @var $OBJECTS array */

/** @var $OAUTH OAuthLogin */

require_once(dirname(__FILE__) . "/inc/load.php");

$MENU->setActive("faq");
$OBJECTS['pageTitle'] = "FAQ";
$TEMPLATE = new Template("content/faq");

echo $TEMPLATE->render($OBJECTS);







