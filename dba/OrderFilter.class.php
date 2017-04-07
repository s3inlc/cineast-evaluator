<?php

namespace DBA;

class OrderFilter extends Order {
  private $by;
  private $type;
  /** @var AbstractModelFactory */
  private $overrideFactory;
  
  function __construct($by, $type, $overrideFactory = null) {
    $this->by = $by;
    $this->type = $type;
    $this->overrideFactory = $overrideFactory;
  }
  
  function getQueryString($table = "") {
    if ($table != "") {
      $table = $table . ".";
    }
    else if ($this->overrideFactory != null) {
      $table = $this->overrideFactory->getModelTable() . ".";
    }
    return $table . $this->by . " " . $this->type;
  }
}


