<?php
/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 29.03.17
 * Time: 16:15
 */

require_once(dirname(__FILE__) . "/inc/load.php");
$OBJECTS['pageTitle'] = "Cineast Evaluator";

$USER_SESSION = new UserSession();

if(isset($_POST['answer'])){
  // TODO: handle submitted response
  // TODO: update session validity
}

// TODO: do pooling of comparisons here

$TEMPLATE = new Template("views/compare3");
echo $TEMPLATE->render($OBJECTS);