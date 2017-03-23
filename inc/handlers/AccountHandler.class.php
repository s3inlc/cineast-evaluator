<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 23.03.17
 * Time: 11:59
 */
class AccountHandler extends Handler {
  
  /**
   * @param $action string action type which should be handled
   */
  public function handle($action) {
    switch ($action) {
      case "changePassword":
        $this->changePassword();
        break;
      default:
        UI::addErrorMessage("Unknown action!");
        break;
    }
  }
  
  private function changePassword() {
    /** @var $LOGIN Login */
    global $FACTORIES, $LOGIN;
    
    if (!isset($_POST['oldPassword']) || !isset($_POST['newPassword']) || !isset($_POST['repeatPassword'])) {
      UI::addErrorMessage("All fields are required!");
      return;
    }
    
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];
    $repeatPassword = $_POST['repeatPassword'];
    
    if(strlen($oldPassword) == 0 || strlen($newPassword) == 0 || strlen($repeatPassword) == 0){
      UI::addErrorMessage("Fields cannot be empty!");
      return;
    }
    else if($newPassword != $repeatPassword){
      UI::addErrorMessage("New passwords do not match!");
      return;
    }
    
    $user = $LOGIN->getUser();
    $matching = Encryption::passwordVerify($oldPassword, $user->getPasswordSalt(), $user->getPasswordHash());
    if(!$matching){
      UI::addErrorMessage("Invalid old password entered!");
      return;
    }
    
    $newSalt = Util::randomString(50);
    $newHash = Encryption::passwordHash($newPassword, $newSalt);
    $user->setPasswordSalt($newSalt);
    $user->setPasswordHash($newHash);
    $FACTORIES::getUserFactory()->update($user);
    UI::addSuccessMessage("Password was changed successfully!");
  }
}