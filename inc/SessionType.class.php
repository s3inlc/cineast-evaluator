<?php

class SessionType {
  const SESSION_TYPE_FREE        = "free";
  const SESSION_TYPE_MICROWORKER = "micro";
  const SESSION_TYPE_USER        = "user";
  const SESSION_TYPE_PLAYER      = "player";
  
  private $type = SessionType::SESSION_TYPE_FREE;
  private $id   = 0;
  
  public function __construct() {
    global $LOGIN, $OAUTH;
    
    // check if it's an admin user
    if ($LOGIN->isLoggedin()) {
      $this->type = SessionType::SESSION_TYPE_USER;
      $this->id = $LOGIN->getUserID();
      return;
    }
    
    // check if it's a player
    if ($OAUTH->isLoggedin()) {
      $this->type = SessionType::SESSION_TYPE_PLAYER;
      $this->id = $OAUTH->getPlayer()->getId();
      return;
    }
    
    // TODO: load microworker Id here
  }
  
  public function getId() {
    return $this->id;
  }
  
  public function getType() {
    return $this->type;
  }
}