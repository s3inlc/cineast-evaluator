<?php
/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 24.04.17
 * Time: 15:12
 */

use DBA\Player;
use DBA\QueryFilter;

require_once(dirname(__FILE__) . "/inc/load.php");

session_start();

$client = new Google_Client();
$client->setAuthConfigFile(dirname(__FILE__) . '/inc/oauth_google_clients_secret.json');
$client->setRedirectUri('https://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php');
$client->addScope(Google_Service_Oauth2::USERINFO_PROFILE);

if (!isset($_GET['code'])) {
  $auth_url = $client->createAuthUrl();
  header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
}
else {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  
  $userinfo = json_decode(Util::getUserInfo($client->getAccessToken()['access_token']), true);
  
  // check if player exists with this oauth id
  $qF = new QueryFilter(Player::OAUTH_ID, $userinfo['id'], "=");
  $player = $FACTORIES::getPlayerFactory()->filter(array($FACTORIES::FILTER => $qF), true);
  if ($player == null) {
    $player = new Player(0, $userinfo['name'], $userinfo['id'], time(), 0);
    $player = $FACTORIES::getPlayerFactory()->save($player);
  }
  $player->setLastLogin(time());
  $FACTORIES::getPlayerFactory()->update($player);
  
  // start user session
  $_SESSION['playerId'] = $player->getId();
  
  header('Location: index.php');
}