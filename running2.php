<?php

use DBA\RandOrderFilter;

require_once(dirname(__FILE__) . "/inc/load.php");
$TEMPLATE = new Template("views/compare2");
$OBJECTS['pageTitle'] = "Cineast Evaluator";

$oF = new RandOrderFilter(1);
$resultSet = $FACTORIES::getResultTupleFactory()->filter(array($FACTORIES::ORDER => $oF), 1);

if ($resultSet == null) {
  UI::addErrorMessage("There is no data available!");
}
else {
  if (mt_rand(0, 1) == 0) {
    $mediaObject1 = $FACTORIES::getMediaObjectFactory()->get($resultSet->getObjectId1());
    $mediaObject2 = $FACTORIES::getMediaObjectFactory()->get($resultSet->getObjectId2());
  }
  else {
    $mediaObject1 = $FACTORIES::getMediaObjectFactory()->get($resultSet->getObjectId2());
    $mediaObject2 = $FACTORIES::getMediaObjectFactory()->get($resultSet->getObjectId1());
  }
  
  $value1 = new DataSet();
  $value2 = new DataSet();
  
  $value1->addValue('objData', array("serve.php?id=" . $mediaObject1->getChecksum()));
  $value2->addValue('objData', array("serve.php?id=" . $mediaObject2->getChecksum()));
  
  $mediaType1 = $FACTORIES::getMediaTypeFactory()->get($mediaObject1->getMediaTypeId());
  $mediaType2 = $FACTORIES::getMediaTypeFactory()->get($mediaObject2->getMediaTypeId());
  
  $value1->addValue('template', $mediaType1->getTemplate());
  $value2->addValue('template', $mediaType2->getTemplate());
  
  $OBJECTS['object1'] = $mediaObject1;
  $OBJECTS['object2'] = $mediaObject2;
  $OBJECTS['value1'] = $value1;
  $OBJECTS['value2'] = $value2;
}

$lastAnswer = false;
if (isset($_POST['answer'])) {
  $arr = array("No Similarity", "Slightly Similar", "Very Similar", "Nearly Identical");
  $lastAnswer = "Your last answer was '" . $arr[$_POST['answer']] . "'.";
}
$OBJECTS['lastAnswer'] = $lastAnswer;

echo $TEMPLATE->render($OBJECTS);