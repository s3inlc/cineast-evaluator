<?php
use DBA\MediaObject;
use DBA\QueryFilter;

$OVERRIDELOGIN = true;
require_once(dirname(__FILE__) . "/inc/load.php");

$path = explode("/", $_SERVER['REQUEST_URI']);
$id = $path[sizeof($path) - 1];

if (!isset($_GET['id']) && !$id) {
  die("Invalid");
}

if (isset($_GET['id'])) {
  $hash = $_GET['id'];
}
else {
  $hash = $id;
}
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










