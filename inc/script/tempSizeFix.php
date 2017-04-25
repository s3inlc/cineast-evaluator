<?php
/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 25.04.17
 * Time: 18:55
 */

require_once(dirname(__FILE__) . "/../load.php");

/**
 * This script is to go through all mediaObjects and checks if it's too big and should be resized
 * this is used to reduce images which were imported before the resizing during import was added
 */

$mediaObjects = $FACTORIES::getMediaObjectFactory()->filter(array());
$total = sizeof($mediaObjects);
$count = 0;
foreach ($mediaObjects as $mediaObject) {
  if($mediaObject->getMediaTypeId() > 0 && $mediaObject->getMediaTypeId() < 4){
    Util::resizeImage($mediaObject->getFilename());
  }
  $count++;
  if ($count % 100 == 0) {
    echo "$count/$total...      \r";
  }
}
echo "finished!\n";
