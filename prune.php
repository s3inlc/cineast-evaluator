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

$question = Util::getRandomQuestion();
Util::prepare2CompareQuestion($question->getMediaObjects()[0], $question->getMediaObjects()[1], true);

$TEMPLATE = new Template("views/prune");
echo $TEMPLATE->render($OBJECTS);