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
      default:
        UI::addErrorMessage("Unknown action!");
        break;
    }
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