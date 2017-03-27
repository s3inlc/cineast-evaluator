<?php
/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 22.03.17
 * Time: 17:35
 */

/** @var $OBJECTS array */

require_once(dirname(__FILE__) . "/inc/load.php");
$TEMPLATE = new Template("content/home");

$MENU->setActive("home");
$OBJECTS['pageTitle'] = "TODO: good name";

echo $TEMPLATE->render($OBJECTS);