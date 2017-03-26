<?php
/**
 * Created by IntelliJ IDEA.
 * User: Sein Coray
 * Date: 3/26/2017
 * Time: 2:20 PM
 */

use DBA\OrderFilter;
use DBA\ResultTuple;

require_once(dirname(__FILE__) . "/inc/load.php");
$TEMPLATE = new Template("views/compare2");
$OBJECTS['pageTitle'] = "Cineast Evaluator";

$oF = new OrderFilter("RAND()", "LIMIT 1");
$entry = $FACTORIES::getResultTupleFactory()->filter(array($FACTORIES::ORDER => $oF), 1);

if($entry == null){
  UI::addErrorMessage("There is no data available!");
}
else{
  $mediaObject1 = $FACTORIES::getMediaObjectFactory()->get($entry->getObjectId1());
  $mediaObject2 = $FACTORIES::getMediaObjectFactory()->get($entry->getObjectId2());
  
  $value1 = new DataSet();
  $value2 = new DataSet();
  
  $value1->addValue('objData', array("data:".mime_content_type($mediaObject1->getFilename()).";base64,".base64_encode(file_get_contents($mediaObject1->getFilename()))));
  $value2->addValue('objData', array("data:".mime_content_type($mediaObject2->getFilename()).";base64,".base64_encode(file_get_contents($mediaObject2->getFilename()))));
  
  $mediaType1 = $FACTORIES::getMediaTypeFactory()->get($mediaObject1->getMediaTypeId());
  $mediaType2 = $FACTORIES::getMediaTypeFactory()->get($mediaObject2->getMediaTypeId());
  
  $value1->addValue('template', $mediaType1->getTemplate());
  $value2->addValue('template', $mediaType2->getTemplate());
  
  $OBJECTS['object1'] = $mediaObject1;
  $OBJECTS['object2'] = $mediaObject2;
  $OBJECTS['value1'] = $value1;
  $OBJECTS['value2'] = $value2;
}

echo $TEMPLATE->render($OBJECTS);