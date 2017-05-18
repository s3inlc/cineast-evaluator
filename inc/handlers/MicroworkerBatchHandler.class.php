<?php

use DBA\Microworker;
use DBA\MicroworkerBatch;
use DBA\QueryFilter;

class MicroworkerBatchHandler extends Handler {
  
  /**
   * @param $action string action type which should be handled
   */
  public function handle($action) {
    switch ($action) {
      case "createBatch":
        $this->create();
        break;
      case "toggleLock":
        $this->toggleLock();
        break;
      case "lockAll":
        $this->toggleAll(true);
        break;
      case "unlockAll":
        $this->toggleAll(false);
        break;
      case "download":
        $this->download();
        break;
      default:
        UI::addErrorMessage("Unknown action!");
        break;
    }
  }
  
  private function download() {
    global $FACTORIES;
    
    $batch = $FACTORIES::getMicroworkerBatchFactory()->get($_POST['batchId']);
    if ($batch == null) {
      UI::addErrorMessage("Invalid Microworker Batch!");
      return;
    }
    
    $qF = new QueryFilter(Microworker::MICROWORKER_BATCH_ID, $batch->getId(), "=");
    $microworkers = $FACTORIES::getMicroworkerFactory()->filter(array($FACTORIES::FILTER => $qF));
    
    header("Content-Type: application/force-download");
    header("Content-Description: batch_" . $batch->getId() . ".csv");
    header("Content-Disposition: attachment; filename=\"batch_" . $batch->getId() . ".csv\"");
    
    echo "token\n"; // header
    foreach ($microworkers as $microworker) {
      echo $microworker->getToken() . "\n";
    }
    die();
  }
  
  private function toggleAll($lock) {
    global $FACTORIES;
    
    $batch = $FACTORIES::getMicroworkerBatchFactory()->get($_POST['batchId']);
    if ($batch == null) {
      UI::addErrorMessage("Invalid Microworker Batch!");
      return;
    }
    $locked = 0;
    if ($lock) {
      $locked = 1;
    }
    $uS = new UpdateSet(Microworker::IS_LOCKED, $locked);
    $qF = new QueryFilter(Microworker::MICROWORKER_BATCH_ID, $batch->getId(), "=");
    $FACTORIES::getMicroworkerFactory()->massUpdate(array($FACTORIES::FILTER => $qF, $FACTORIES::UPDATE => $uS));
    UI::addSuccessMessage("Applied mass action successfully!");
  }
  
  private function toggleLock() {
    global $FACTORIES;
    
    $microworker = $FACTORIES::getMicroworkerFactory()->get($_POST['microworkerId']);
    if ($microworker == null) {
      UI::addErrorMessage("Invalid microworker!");
      return;
    }
    if ($microworker->getIsLocked() == 0) {
      $microworker->setIsLocked(1);
      UI::addSuccessMessage("Microworker successfully locked!");
    }
    else {
      $microworker->setIsLocked(0);
      UI::addSuccessMessage("Microworker successfully unlocked!");
    }
    $FACTORIES::getMicroworkerFactory()->update($microworker);
  }
  
  private function create() {
    global $FACTORIES;
    
    $numTokens = intval($_POST['numTokens']);
    if ($numTokens < 1) {
      UI::addErrorMessage("Invalid number of tokens!");
      return;
    }
    $batch = new MicroworkerBatch(0, time());
    $batch = $FACTORIES::getMicroworkerBatchFactory()->save($batch);
    $microworkers = array();
    for ($i = 0; $i < $numTokens; $i++) {
      do {
        $token = Util::randomString(40);
        $qF = new QueryFilter(Microworker::TOKEN, $token, "=");
        $check = $FACTORIES::getMicroworkerFactory()->filter(array($FACTORIES::FILTER => $qF), true);
      } while ($check != null);
      $microworkers[] = new Microworker(0, $batch->getId(), $token, 1, 0, 0, "", 0);
    }
    $FACTORIES::getMicroworkerFactory()->massSave($microworkers);
    header("Location: microworkers.php");
    die();
  }
}