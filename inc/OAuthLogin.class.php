<?php
/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 24.04.17
 * Time: 16:53
 */

class OAuthLogin {
  /**
   * OAuthLogin constructor.
   */
  public function __construct() {
    // TODO: check if a oauth user session is active
  }
  
  /**
   * @return bool
   */
  public function isLoggedin(){
    // TODO:
    return false;
  }
  
  public function login(){
    // TODO: log user in with OAuth
  }
  
  public function logout(){
    // TODO: log user out
  }
  
  public function getPlayer(){
    // TODO: return player which is assigned to this login session
    return null;
  }
}