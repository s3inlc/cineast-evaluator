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
    
    $qF = new QueryFilter(Player::EMAIL, $email, "=");
    $check = $FACTORIES::getPlayerFactory()->filter(array($FACTORIES::FILTER => $qF), true);
    if ($check != null) {
      UI::addErrorMessage("This user already has an account!");
      return;
    }
    
    $emailContent = new Template("email/invite");
    
    /*$script = file_get_contents("https://code.jquery.com/jquery-2.1.1.min.js") . "\n";
    $script .= file_get_contents("https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js") . "\n";
    $script .= file_get_contents(dirname(__FILE__) . "/../../js/init.js") . "\n";
    
    $style = file_get_contents("https://fonts.googleapis.com/icon?family=Material+Icons") . "\n";
    $style .= file_get_contents("https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/css/materialize.min.css") . "\n";
    $style .= file_get_contents(dirname(__FILE__) . "/../../css/style.css") . "\n";*/
    
    $emailObj = array(
      "GameName" => GAME_NAME,
      "playerName" => $OAUTH->getPlayer()->getPlayerName(),
      "affiliateKey" => $OAUTH->getPlayer()->getAffiliateKey(),
      "Domain" => DOMAIN,
      /*"Script" => $script,
      "Style" => $style,
      "Logo" => "data:image/png;base64," . base64_encode(file_get_contents(dirname(__FILE__) . "/../../static/logo.png"))*/
    );
    Util::sendMail($email, "Invitation to " . GAME_NAME, $emailContent->render($emailObj));
    UI::addSuccessMessage("Invitation email was sent!");
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
    $oldUsername = $player->getPlayerName();
    $player->setPlayerName($newUsername);
    if ($oldUsername != $newUsername) {
      $player->setIsInitialName(0);
    }
    $FACTORIES::getPlayerFactory()->update($player);
    $OAUTH->updatePlayerName($newUsername);
    UI::addSuccessMessage("New player name was set successfully!");
  }
}