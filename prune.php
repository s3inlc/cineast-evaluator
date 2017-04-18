<?php
/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 29.03.17
 * Time: 16:15
 */

require_once(dirname(__FILE__) . "/inc/load.php");
if (!$LOGIN->isLoggedin()) {
  header("Location: admin.php?err=4" . time());
  die();
}
$OBJECTS['pageTitle'] = "Cineast Evaluator";

session_start();
if (isset($_GET['queryId'])) {
  $query = $FACTORIES::getQueryFactory()->get($_GET['queryId']);
  if ($query != null) {
    $_SESSION['queryId'] = $query->getId();
    $_SESSION['lastId'] = 0;
    header("Location: prune.php");
  }
}
else {
  $lastId = 0;
  $queryId = 0;
  if (isset($_SESSION['queryId'])) {
    $queryId = $_SESSION['queryId'];
  }
  if (isset($_SESSION['lastId'])) {
    $lastId = $_SESSION['lastId'];
  }
}

// TODO: process answers here

$question = Util::getNextPruneQuestion($queryId, $lastId);

if ($question == null) {
  // TODO: make fancy forward here
  die("You went trough all answers!");
}

Util::prepare2CompareQuestion($question->getMediaObjects()[0], $question->getMediaObjects()[1], true);

$TEMPLATE = new Template("views/prune");
echo $TEMPLATE->render($OBJECTS);