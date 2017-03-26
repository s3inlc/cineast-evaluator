<?php
/**
 * Created by IntelliJ IDEA.
 * User: Sein Coray
 * Date: 3/26/2017
 * Time: 3:00 PM
 */

use DBA\MediaObject;
use DBA\QueryFilter;

require_once(dirname(__FILE__) . "/inc/load.php");

if (!isset($_GET['id'])) {
  die();
}

$hash = $_GET['id'];
$qF = new QueryFilter(MediaObject::CHECKSUM, $hash, "=");
$result = $FACTORIES::getMediaObjectFactory()->filter(array($FACTORIES::FILTER => $qF), true);

if ($result == null) {
  die();
}

$file = fopen(STORAGE_PATH . MEDIA_FOLDER . $hash, "rb");
while(!feof($file)){
  $data = fread($file, 4096);
  echo $data;
}
fclose($file);










