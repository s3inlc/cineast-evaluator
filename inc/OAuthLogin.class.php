<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 24.04.17
 * Time: 16:53
 */
class OAuthLogin {
  private $client = null;
  private $valid  = false;
  private $player = null;
  
  /**
   * OAuthLogin constructor.
   */
  public function __construct() {
    global $FACTORIES;
    
    $this->valid = false;
    $this->client = new Google_Client();
    $this->client->setAuthConfig(dirname(__FILE__) . '/oauth_google_clients_secret.json');
    $this->client->addScope(Google_Service_Oauth2::USERINFO_PROFILE);
    
    if (isset($_SESSION['access_token']) && $_SESSION['access_token'] && isset($_SESSION['playerId']) && $_SESSION['playerId']) {
      $this->client->setAccessToken($_SESSION['access_token']);
      if ($this->client->isAccessTokenExpired()) {
        $this->client->refreshToken($_SESSION['access_token']);
        $_SESSION['access_token'] = $this->client->getAccessToken();
        if (!$_SESSION['access_token']) {
          unset($_SESSION['access_token']);
        }
      }
      else {
        $this->player = $FACTORIES::getPlayerFactory()->get($_SESSION['playerId']);
        $this->valid = true;
      }
    }
  }
  
  /**
   * @return bool
   */
  public function isLoggedin() {
    return $this->valid;
  }
  
  public function login($refer) {
    if ($this->isLoggedin()) {
      return;
    }
    header('Location: oauth2callback.php?refer=' . urlencode($refer));
    die();
  }
  
  public function logout() {
    unset($_SESSION['access_token']);
    $this->client = null;
    $this->valid = false;
    $this->player = null;
  }
  
  public function getPlayer() {
    if (!$this->isLoggedin()) {
      return null;
    }
    return $this->player;
  }
}