<?php
use DBA\Player;
use DBA\QueryFilter;

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
  private $type   = OAuthLogin::TYPE_UNDEFINED;
  
  const TYPE_UNDEFINED = "undefined";
  const TYPE_GOOGLE    = "google";
  const TYPE_FACEBOOK  = "facebook";
  
  /**
   * OAuthLogin constructor.
   */
  public function __construct() {
    global $FACTORIES;
    
    if (isset($_SESSION['accessToken']) && $_SESSION['accessToken'] && isset($_SESSION['playerId']) && $_SESSION['playerId']) {
      $this->type = $_SESSION['sessionType'];
      switch ($this->type) {
        case OAuthLogin::TYPE_GOOGLE:
          $this->client = new Google_Client();
          $this->client->setAuthConfig(dirname(__FILE__) . '/oauth_google_clients_secret.json');
          $this->client->addScope(Google_Service_Oauth2::USERINFO_PROFILE);
          $this->client->setAccessToken($_SESSION['accessToken']);
          if ($this->client->isAccessTokenExpired()) {
            $this->client->refreshToken($_SESSION['accessToken']);
            $_SESSION['accessToken'] = $this->client->getAccessToken();
            if (!$_SESSION['accessToken']) {
              unset($_SESSION['accessToken']);
            }
          }
          else {
            $this->player = $FACTORIES::getPlayerFactory()->get($_SESSION['playerId']);
            $this->valid = true;
          }
          break;
        case OAuthLogin::TYPE_FACEBOOK:
          $fb = new Facebook\Facebook(json_decode(file_get_contents(dirname(__FILE__) . '/oauth_facebook_clients_secret.json'), true));
          $helper = $fb->getCanvasHelper();
          try {
            $helper->getAccessToken();
            $this->valid = true;
            $this->player = $FACTORIES::getPlayerFactory()->get($_SESSION['playerId']);
          }
          catch (\Facebook\Exceptions\FacebookAuthenticationException $e) {
            $this->valid = false;
            unset($_SESSION['accessToken']);
            unset($_SESSION['playerId']);
            $this->login("", OAuthLogin::TYPE_FACEBOOK);
          }
          break;
        case OAuthLogin::TYPE_UNDEFINED:
        default:
          unset($_SESSION['accessToken']);
          unset($_SESSION['playerId']);
          $this->valid = false;
          break;
      }
    }
    
    if ($this->valid && strlen($this->player->getAffiliateKey()) == 0) {
      do {
        $key = Util::randomString(10);
        echo "KEY: $key";
        $qF = new QueryFilter(Player::AFFILIATE_KEY, $key, "=");
        $check = $FACTORIES::getPlayerFactory()->filter(array($FACTORIES::FILTER => $qF));
      } while (sizeof($check) > 0);
      $this->player->setAffiliateKey($key);
      $FACTORIES::getPlayerFactory()->update($this->player);
    }
  }
  
  /**
   * @return bool
   */
  public function isLoggedin() {
    return $this->valid;
  }
  
  public function login($refer, $type) {
    if ($this->isLoggedin() && $type == $this->type) {
      return;
    }
    header('Location: oauth2callback.php?refer=' . urlencode($refer) . "&provider=" . urlencode($type));
    die();
  }
  
  public function logout() {
    unset($_SESSION['accessToken']);
    unset($_SESSION['playerId']);
    $this->client = null;
    $this->valid = false;
    $this->player = null;
    $this->type = OAuthLogin::TYPE_UNDEFINED;
  }
  
  public function getPlayer() {
    if (!$this->isLoggedin()) {
      return null;
    }
    return $this->player;
  }
  
  public function updatePlayerName($newUsername) {
    $this->player->setPlayerName($newUsername);
  }
}