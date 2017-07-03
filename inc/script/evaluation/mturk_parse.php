<?php

require_once(dirname(__FILE__) . "/../../load.php");

$files = scandir(dirname(__FILE__) . "/mturk");
$uniqueWorkers = array("all" => array());
foreach ($files as $file) {
  if ($file[0] == '.') {
    continue;
  }
  $fileHandle = fopen(dirname(__FILE__) . "/mturk/" . $file, "r");
  $firstLine = null;
  while ($line = fgetcsv($fileHandle)) {
    if ($line[0] == null) {
      continue; // skip empty line
    }
    if ($firstLine == null) {
      $firstLine = $line;
      continue;
    }
    // workerid is at position 15
    $workerId = $line[15];
    
    // add worker to all
    if (!isset($uniqueWorkers["all"][$workerId])) {
      $uniqueWorkers["all"][$workerId] = 1;
    }
    else {
      $uniqueWorkers["all"][$workerId]++;
    }
    
    $file = str_replace(".csv", "", $file);
    
    // create batch specific array if needed
    if (!isset($uniqueWorkers[$file])) {
      $uniqueWorkers[$file] = array();
    }
    
    if (!isset($uniqueWorkers[$file][$workerId])) {
      $uniqueWorkers[$file][$workerId] = 1;
    }
    else {
      $uniqueWorkers[$file][$workerId]++;
    }
  }
  fclose($fileHandle);
}

$workers = array("all" => array());
foreach ($uniqueWorkers as $type => $list) {
  if (!isset($workers[$type])) {
    $workers[$type] = array();
  }
  foreach ($list as $workerId => $count) {
    $workers[$type][] = array("workerId" => $workerId, "count" => $count);
  }
}

foreach ($workers as $type => $worker) {
  saveCSV($worker, dirname(__FILE__) . "/output/" . $type . "Workers.csv");
}


/**
 * @param $elements array
 * @param $path
 */
function saveCSV($elements, $path) {
  if (sizeof($elements) == 0) {
    return;
  }
  $header = array();
  $arr = $elements[0];
  foreach ($arr as $key => $val) {
    $header[] = $key;
  }
  $output = implode(",", $header) . "\n";
  foreach ($elements as $element) {
    $output .= implode(",", $element) . "\n";
  }
  file_put_contents($path, $output);
}