<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 29.03.17
 * Time: 16:51
 */
class SessionType {
  const SESSION_TYPE_FREE        = "free";
  const SESSION_TYPE_MICROWORKER = "micro";
  const SESSION_TYPE_USER        = "user";
  const SESSION_TYPE_PLAYER      = "player";
  
  private $type = SessionType::SESSION_TYPE_FREE;
  
  public function __construct() {
    global $LOGIN;
    
    if($LOGIN->isLoggedin()){
      $this->type = SessionType::SESSION_TYPE_USER;
      return;
    }
  }
  
  public function getType() {
    return $this->type;
  }
}