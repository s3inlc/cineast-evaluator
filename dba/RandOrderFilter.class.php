<?php

namespace DBA;

class RandOrderFilter extends Order {
  private $limit;
  
  function __construct($limit) {
    $this->limit = $limit;
  }
  
  function getQueryString($table = "") {
    return " RAND() LIMIT " . $this->limit;
  }
}


