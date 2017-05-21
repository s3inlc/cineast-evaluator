<?php

use DBA\Player;
use DBA\QueryFilter;

class SettingsHandler extends Handler {
  
  /**
   * @param $action string action type which should be handled
   */
  public function handle($action) {
    switch ($action) {
      case "changeUsername":
        $this->changeUsername();
        break;
      case "invite":
        $this->inviteUser();
        break;
      default:
        UI::addErrorMessage("Unknown action!");
        break;
    }
  }
  
  private function inviteUser() {
    /** @var $OAUTH OAuthLogin */
    global $FACTORIES, $OAUTH;
    
    $email = $_POST['email'];
    if (strlen($email) == 0 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      UI::addErrorMessage("Please enter a valid email address!");
      return;
    }
    $emailContent = new Template("email/invite");
    $emailObj = array(
      "GameName" => GAME_NAME,
      "playerName" => $OAUTH->getPlayer()->getPlayerName(),
      "affiliateKey" => $OAUTH->getPlayer()->getAffiliateKey()
    );
  }
  
  private function changeUsername() {
    /** @var $OAUTH OAuthLogin */
    global $FACTORIES, $OAUTH;
    
    $newUsername = substr(htmlentities($_POST['playerName'], false, "UTF-8"), 0, 45);
    $qF = new QueryFilter(Player::PLAYER_NAME, $newUsername, "=");
    $check = $FACTORIES::getPlayerFactory()->filter(array($FACTORIES::FILTER => $qF), true);
    if ($check != null) {
      UI::addErrorMessage("This player name is already used!");
      return;
    }
    $player = $OAUTH->getPlayer();
    $player->setPlayerName($newUsername);
    $FACTORIES::getPlayerFactory()->update($player);
    $OAUTH->updatePlayerName($newUsername);
    UI::addSuccessMessage("New player name was set successfully!");
  }
}