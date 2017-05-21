<?php
/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 24.04.17
 * Time: 15:12
 */

use DBA\Player;
use DBA\QueryFilter;
use DBA\Oauth;

/** @var $OAUTH OAuthLogin */

require_once(dirname(__FILE__) . "/inc/load.php");

$provider = @$_GET['provider'];
if (!$provider) {
  $provider = @$_SESSION['provider'];
}
if ($provider != OAuthLogin::TYPE_FACEBOOK && $provider != OAuthLogin::TYPE_GOOGLE) {
  header('HTTP/1.0 400 Bad Request');
  echo 'Bad request';
  die();
}

if (!isset($_GET['code']) && $provider == OAuthLogin::TYPE_GOOGLE) {
  $client = new Google_Client();
  $client->setAuthConfigFile(dirname(__FILE__) . '/inc/oauth_google_clients_secret.json');
  $client->setRedirectUri('https://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php');
  $client->addScope(Google_Service_Oauth2::USERINFO_PROFILE);
  $client->addScope(Google_Service_Oauth2::USERINFO_EMAIL);
  $auth_url = $client->createAuthUrl();
  $_SESSION['provider'] = OAuthLogin::TYPE_GOOGLE;
  header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
  die();
}
else if ($provider == OAuthLogin::TYPE_GOOGLE) {
  $client = new Google_Client();
  $client->setAuthConfigFile(dirname(__FILE__) . '/inc/oauth_google_clients_secret.json');
  $client->setRedirectUri('https://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php');
  $client->addScope(Google_Service_Oauth2::USERINFO_PROFILE);
  $client->addScope(Google_Service_Oauth2::USERINFO_EMAIL);
  $client->authenticate($_GET['code']);
  $_SESSION['accessToken'] = $client->getAccessToken();
  
  $userinfo = json_decode(Util::getUserInfo($client->getAccessToken()['access_token']), true);
}
else if ($provider == OAuthLogin::TYPE_FACEBOOK) {
  if (isset($_GET['provider'])) {
    $fb = new Facebook\Facebook(json_decode(file_get_contents(dirname(__FILE__) . '/inc/oauth_facebook_clients_secret.json'), true));
    $helper = $fb->getRedirectLoginHelper();
    
    $permissions = ['email']; // Optional permissions
    $_SESSION['provider'] = OAuthLogin::TYPE_FACEBOOK;
    $loginUrl = $helper->getLoginUrl('https://dev-evaluate.vitrivr.org/oauth2callback.php', $permissions);
    header("Location: " . $loginUrl);
    die();
  }
  
  $fb = new Facebook\Facebook(json_decode(file_get_contents(dirname(__FILE__) . '/inc/oauth_facebook_clients_secret.json'), true));
  $helper = $fb->getRedirectLoginHelper();
  
  try {
    $accessToken = $helper->getAccessToken();
  }
  catch (Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    die();
  }
  catch (Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    die();
  }
  
  if (!isset($accessToken)) {
    if ($helper->getError()) {
      header('HTTP/1.0 401 Unauthorized');
      echo "Error: " . $helper->getError() . "\n";
      echo "Error Code: " . $helper->getErrorCode() . "\n";
      echo "Error Reason: " . $helper->getErrorReason() . "\n";
      echo "Error Description: " . $helper->getErrorDescription() . "\n";
    }
    else {
      header('HTTP/1.0 400 Bad Request');
      echo 'Bad request';
    }
    die();
  }
  
  $oAuth2Client = $fb->getOAuth2Client();
  
  if (!$accessToken->isLongLived()) {
    // Exchanges a short-lived access token for a long-lived one
    try {
      $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
    }
    catch (Facebook\Exceptions\FacebookSDKException $e) {
      echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
      die();
    }
  }
  
  $_SESSION['accessToken'] = (string)$accessToken;
  try {
    // Returns a `Facebook\FacebookResponse` object
    $response = $fb->get('/me?fields=id,name', $accessToken);
  }
  catch (Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    die();
  }
  catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    die();
  }
  $user = $response->getGraphUser();
  $userinfo['id'] = $user->getId();
  $userinfo['name'] = $user->getName();
  $userinfo['email'] = $user->getEmail();
}
else {
  header('HTTP/1.0 400 Bad Request');
  echo 'Bad request';
  die();
}

// check if player exists with this oauth id
$qF1 = new QueryFilter(Oauth::OAUTH_IDENTIFIER, $userinfo['id'], "=");
$qF2 = new QueryFilter(Oauth::TYPE, $provider, "=");
$oauth = $FACTORIES::getOauthFactory()->filter(array($FACTORIES::FILTER => array($qF1, $qF2)), true);
if ($oauth == null) {
  // check if player is logged in with another provider
  if ($OAUTH->isLoggedin()) {
    $player = $OAUTH->getPlayer();
  }
  else {
    $player = new Player(0, $userinfo['name'], $userinfo['email'], "", "");
    $player = $FACTORIES::getPlayerFactory()->save($player);
  }
  $oauth = new Oauth(0, $player->getId(), $provider, time(), time(), $userinfo['id']);
  $oauth = $FACTORIES::getOauthFactory()->save($oauth);
}
$oauth->setLastLogin(time());
$FACTORIES::getOauthFactory()->update($oauth);

if (!isset($player)) {
  $player = $FACTORIES::getPlayerFactory()->get($oauth->getPlayerId());
}
if (strlen($player->getEmail()) == 0 && strlen($userinfo['email']) > 0) {
  $player->setEmail($userinfo['email']);
  $FACTORIES::getPlayerFactory()->update($player);
}

// start user session
$_SESSION['playerId'] = $oauth->getPlayerId();
$_SESSION['sessionType'] = $provider;
if (isset($_SESSION['answerSessionId'])) {
  $answerSession = $FACTORIES::getAnswerSessionFactory()->get($_SESSION['answerSessionId']);
  if ($answerSession->getIsOpen() == 0 && $answerSession->getPlayerId() == null && $answerSession->getMicroworkerId() == null && $answerSession->getUserId() == null) {
    $answerSession->setPlayerId($oauth->getPlayerId());
    $FACTORIES::getAnswerSessionFactory()->update($answerSession);
    header("Location: score.php");
    die();
  }
}

header('Location: index.php');
