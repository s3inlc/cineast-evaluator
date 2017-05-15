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
  $client->authenticate($_GET['code']);
  $_SESSION['accessToken'] = $client->getAccessToken();
  
  $userinfo = json_decode(Util::getUserInfo($client->getAccessToken()['access_token']), true);
  
  // check if player exists with this oauth id
  $qF = new QueryFilter(Oauth::OAUTH_IDENTIFIER, $userinfo['id'], "=");
  $oauth = $FACTORIES::getOauthFactory()->filter(array($FACTORIES::FILTER => $qF), true);
  if ($oauth == null) {
    // check if player is logged in with another provider
    if ($OAUTH->isLoggedin()) {
      $player = $OAUTH->getPlayer();
    }
    else {
      $player = new Player(0, $userinfo['name']);
      $player = $FACTORIES::getPlayerFactory()->save($player);
    }
    $oauth = new Oauth(0, $player->getId(), $provider, time(), time(), $userinfo['id']);
    $oauth = $FACTORIES::getOauthFactory()->save($oauth);
  }
  $oauth->setLastLogin(time());
  $FACTORIES::getOauthFactory()->update($oauth);
}
else if ($provider == OAuthLogin::TYPE_FACEBOOK) {
  if (isset($_GET['provider'])) {
    $fb = new Facebook\Facebook(json_decode(file_get_contents(dirname(__FILE__) . '/inc/oauth_facebook_clients_secret.json'), true));
    $helper = $fb->getRedirectLoginHelper();
    
    $permissions = ['email']; // Optional permissions
    $loginUrl = $helper->getLoginUrl('https://dev-evaluate.vitrivr.org/oauth2callback.php', $permissions);
    header("Location: " . $loginUrl);
    die();
  }
  
  $fb = new Facebook\Facebook(json_encode(file_get_contents(dirname(__FILE__) . '/inc/oauth_facebook_clients_secret.json')));
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
  
  $tokenMetadata = $oAuth2Client->debugToken($accessToken);
  echo '<h3>Metadata</h3>';
  var_dump($tokenMetadata);
  
  $tokenMetadata->validateAppId(file_get_contents(dirname(__FILE__) . '/inc/oauth_facebook_clients_app.txt'));
  // If you know the user ID this access token belongs to, you can validate it here
  //$tokenMetadata->validateUserId('123');
  $tokenMetadata->validateExpiration();
  
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
  
  //$_SESSION['accessToken'] = (string)$accessToken;
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
  print_r($user);
  die();
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
