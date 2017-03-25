<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 23.03.17
 * Time: 11:59
 */
class QueryHandler extends Handler {
  
  /**
   * @param $action string action type which should be handled
   */
  public function handle($action) {
    switch ($action) {
      case "addQuery":
        $this->addQuery();
        break;
      default:
        UI::addErrorMessage("Unknown action!");
        break;
    }
  }
  
  private function addQuery() {
    global $FACTORIES;
    
    $path = STORAGE_PATH . TMP_FOLDER . "import-" . time() . "/";
    $filename = $path . "import.zip";
    
    if($_FILES['file']['error'] != 0){
      UI::addErrorMessage("Error happened on file upload!");
      return;
    }
    else if(strpos($_FILES['file']['name'], ".zip") === false){
      UI::addErrorMessage("File must be uploaded as .zip archive!");
      return;
    }
    
    if (!move_uploaded_file($_FILES['file']['tmp_name'], $filename)) {
      UI::addErrorMessage("Failed to move uploaded file into storage directory!");
      return;
    }
    // upload was successful
    // processing the .zip now
    $output = exec("cd '$path' && unzip '$filename'");
    print_r($output);
    
    // clean up
    system("rm -r '$path'");
  }
}