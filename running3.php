<?php
/**
 * Created by IntelliJ IDEA.
 * User: Sein Coray
 * Date: 3/26/2017
 * Time: 2:20 PM
 */

use DBA\QueryFilter;
use DBA\RandOrderFilter;
use DBA\ResultTuple;

require_once(dirname(__FILE__) . "/inc/load.php");
$TEMPLATE = new Template("views/compare3");
$OBJECTS['pageTitle'] = "Cineast Evaluator";

// TODO: do pooling of comparisons here
// TODO: handle submitted responses
// TODO: open sessions
// TODO: validate sessions


$oF = new RandOrderFilter(1);
$resultSet1 = $FACTORIES::getResultTupleFactory()->filter(array($FACTORIES::ORDER => $oF), true);

if ($resultSet1 == null) {
  UI::addErrorMessage("There is no data available!");
}
else {
  $qF1 = new QueryFilter(ResultTuple::OBJECT_ID1, $resultSet1->getObjectId1(), "=");
  $qF2 = new QueryFilter(ResultTuple::RESULT_TUPLE_ID, $resultSet1->getId(), "<>");
  $resultSet2 = $FACTORIES::getResultTupleFactory()->filter(array($FACTORIES::FILTER => array($qF1, $qF2), $FACTORIES::ORDER => $oF), true);
  
  $mediaObject1 = $FACTORIES::getMediaObjectFactory()->get($resultSet1->getObjectId1());
  if (mt_rand(0, 1) == 0) {
    $mediaObject2 = $FACTORIES::getMediaObjectFactory()->get($resultSet1->getObjectId2());
    $mediaObject3 = $FACTORIES::getMediaObjectFactory()->get($resultSet2->getObjectId2());
  }
  else {
    $mediaObject2 = $FACTORIES::getMediaObjectFactory()->get($resultSet2->getObjectId2());
    $mediaObject3 = $FACTORIES::getMediaObjectFactory()->get($resultSet1->getObjectId2());
  }
  
  $value1 = new DataSet();
  $value2 = new DataSet();
  $value3 = new DataSet();
  
  $value1->addValue('objData', array("serve.php?id=" . $mediaObject1->getChecksum()));
  $value2->addValue('objData', array("serve.php?id=" . $mediaObject2->getChecksum()));
  $value3->addValue('objData', array("serve.php?id=" . $mediaObject3->getChecksum()));
  
  $mediaType1 = $FACTORIES::getMediaTypeFactory()->get($mediaObject1->getMediaTypeId());
  $mediaType2 = $FACTORIES::getMediaTypeFactory()->get($mediaObject2->getMediaTypeId());
  $mediaType3 = $FACTORIES::getMediaTypeFactory()->get($mediaObject3->getMediaTypeId());
  
  $value1->addValue('template', $mediaType1->getTemplate());
  $value2->addValue('template', $mediaType2->getTemplate());
  $value3->addValue('template', $mediaType3->getTemplate());
  
  $OBJECTS['object1'] = $mediaObject1;
  $OBJECTS['object2'] = $mediaObject2;
  $OBJECTS['object3'] = $mediaObject3;
  $OBJECTS['value1'] = $value1;
  $OBJECTS['value2'] = $value2;
  $OBJECTS['value3'] = $value3;
}

$lastAnswer = false;
if(isset($_POST['answer'])){
  $lastAnswer = "Your last answer was '".htmlentities($_POST['answer'], false, "UTF-8")."''.";
}
$OBJECTS['lastAnswer'] = $lastAnswer;

echo $TEMPLATE->render($OBJECTS);