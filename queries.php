<?php
/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 22.03.17
 * Time: 17:35
 */

/** @var $OBJECTS array */

require_once(dirname(__FILE__) . "/inc/load.php");
$TEMPLATE = new Template("content/queries");

$MENU->setActive("queries");
$OBJECTS['pageTitle'] = "Cineast Evaluator";

$queries = $FACTORIES::getQueryFactory()->filter(array());
$OBJECTS['queries'] = $queries;

echo $TEMPLATE->render($OBJECTS);