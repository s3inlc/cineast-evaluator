<?php

/** @var $OBJECTS array */

require_once(dirname(__FILE__) . "/inc/load.php");
$TEMPLATE = new Template("content/data");

$MENU->setActive("data");
$OBJECTS['pageTitle'] = GAME_NAME;

if (isset($_GET['get'])) {
  switch ($_GET['get']) {
    case 'full':
      $path = dirname(__FILE__) . "/inc/script/nightlyFull.tar.gz";
      $name = "nightlyFull.7z";
      break;
    case 'minimal':
      $path = dirname(__FILE__) . "/inc/script/nightlyMinimum.tar.gz";
      $name = "nightlyMinimum.7z";
      break;
  }
  if (strlen($path) > 0) {
    header('Content-Type: application/octet-stream');
    header('Content-Length: ' . filesize($path));
    header('Content-Disposition: attachment; filename="' . $name . '"');
    
    $file = fopen($path, "rb");
    while (!feof($file)) {
      $data = fread($file, 4096);
      echo $data;
    }
    fclose($file);
    die();
  }
}

$OBJECTS['latestBuild'] = filectime(dirname(__FILE__) . "/inc/script/nightlyFull.tar.gz");

echo $TEMPLATE->render($OBJECTS);