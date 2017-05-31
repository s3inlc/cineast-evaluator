<?php
use DBA\Player;
use DBA\QueryFilter;
use DBA\Oauth;

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 25.04.17
 * Time: 15:31
 */
class OAuthFacebookAchievement extends GameAchievement {
  
  /**
   * @return string
   */
  function getAchievementName() {
    return "Facebook User";
  }
  
  function getIsHidden() {
    return false;
  }
  
  /**
   * @param $player Player
   * @return bool
   */
  function isReachedByPlayer($player) {
    global $FACTORIES;
    
    if ($player == null || $this->alreadyReached($player)) {
      return false;
    }
    
    $qF = new QueryFilter(Oauth::PLAYER_ID, $player->getId(), "=");
    $providers = $FACTORIES::getOauthFactory()->filter(array($FACTORIES::FILTER => $qF));
    foreach ($providers as $provider) {
      if ($provider->getType() == OAuthLogin::TYPE_FACEBOOK) {
        return true;
      }
    }
    
    return false;
  }
  
  /**
   * @return string
   */
  function getAchievementImage() {
    return "success.png"; // TODO: add image
  }
  
  /**
   * @return float
   */
  function getMultiplicatorGain() {
    return 1.05;
  }
  
  /**
   * @return string
   */
  function getIdentifier() {
    return "oauthFacebook";
  }
  
  /**
   * @return string
   */
  function getDescription() {
    return "Log in with your Facebook account<br>Gives 5% extra score";
  }
  
  /**
   * @param $player Player
   * @return int progress in %
   */
  function getProgress($player) {
    return 0;
  }
}