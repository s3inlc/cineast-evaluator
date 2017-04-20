<?php
use DBA\MediaObject;
use DBA\QueryFilter;

require_once(dirname(__FILE__) . "/inc/load.php");

if (!isset($_GET['id'])) {
  die("Invalid");
}

$hash = $_GET['id'];
$qF = new QueryFilter(MediaObject::CHECKSUM, $hash, "=");
$result = $FACTORIES::getMediaObjectFactory()->filter(array($FACTORIES::FILTER => $qF), true);

if ($result == null) {
  die("404");
}

header('Content-Type: ' . mime_content_type(STORAGE_PATH . MEDIA_FOLDER . $hash));
header('Content-Length: ' . filesize(STORAGE_PATH . MEDIA_FOLDER . $hash));

$file = fopen(STORAGE_PATH . MEDIA_FOLDER . $hash, "rb");
while (!feof($file)) {
  $data = fread($file, 4096);
  echo $data;
}
fclose($file);










