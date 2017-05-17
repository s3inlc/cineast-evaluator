<?php
use DBA\Microworker;
use DBA\QueryFilter;

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 15.05.17
 * Time: 15:51
 */
class MTurk {
  private $microworkerId;
  private $valid       = false;
  private $microworker = null;
  
  public function __construct() {
    global $FACTORIES;
    
    if (isset($_SESSION['microworkerId'])) {
      $this->microworkerId = $_SESSION['microworkerId'];
      $this->microworker = $FACTORIES::getMicroworkerFactory()->get($this->microworkerId);
      if ($this->microworker != null) {
        $this->valid = true;
      }
    }
  }
  
  public function isMechanicalTurk() {
    return $this->valid;
  }
  
  public function isClosed() {
    return $this->microworker->getTimeClosed() != 0;
  }
}