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
      default:
        UI::addErrorMessage("Unknown action!");
        break;
    }
  }
  
  private function changeUsername() {
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