<?php

use DBA\MediaObject;
use DBA\QueryFilter;

require_once(dirname(__FILE__) . "/../../load.php");

if (!file_exists("sha.txt")) {
  die("File with matching hash to filename is required!\n");
}

$file = fopen("sha.txt", "rb");
while (!feof($file)) {
  $line = trim(fgets($file));
  $split = explode(" ", $line);
  if (sizeof($line) != 2) {
    continue; // ignore invalid lines
  }
  $hash = $split[0];
  unset($split[0]);
  $path = implode(" ", $split);
  
  $qF = new QueryFilter(MediaObject::CHECKSUM, $hash, "=");
  $mediaObjects = $FACTORIES::getMediaObjectFactory()->filter(array($FACTORIES::FILTER => $qF));
  foreach ($mediaObjects as $mediaObject) {
    $mediaObject->setOriginal($path);
    $FACTORIES::getMediaObjectFactory()->update($mediaObject);
  }
}


