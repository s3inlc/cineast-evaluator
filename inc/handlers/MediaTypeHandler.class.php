<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 23.03.17
 * Time: 11:59
 */
class MediaTypeHandler extends Handler {
  
  /**
   * @param $action string action type which should be handled
   */
  public function handle($action) {
    switch ($action) {
      case "updateType":
        $this->updateType();
        break;
      default:
        UI::addErrorMessage("Unknown action!");
        break;
    }
  }
  
  private function updateType(){
    global $FACTORIES;
    
    $typeName = htmlentities($_POST['typeName'], false, "UTF-8");
    $template = htmlentities($_POST['template'], false, "UTF-8");
    
    $mediaType = $FACTORIES::getMediaTypeFactory()->get($_POST['mediaTypeId']);
    if($mediaType == null){
      UI::addErrorMessage("Invalid mediaType!");
      return;
    }
    
    $mediaType->setTemplate($template);
    $mediaType->setTypeName($typeName);
    $FACTORIES::getMediaTypeFactory()->update($mediaType);
    UI::addSuccessMessage("MediaType was updated successfully!");
  }
}