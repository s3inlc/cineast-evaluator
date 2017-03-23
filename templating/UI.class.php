<?php

class UI {
  public static function printError($level, $message) {
    $OBJECTS = array();
    $TEMPLATE = new Template("errors/error");
    $OBJECTS['message'] = $message;
    $OBJECTS['level'] = $level;
    echo $TEMPLATE->render($OBJECTS);
    die();
  }
  
  public static function printFatalError($message) {
    echo $message;
    die();
  }
  
  public static function addMessage($message, $type) {
    global $OBJECTS;
    
    if (!isset($OBJECTS[$type . "Messages"])) {
      $OBJECTS[$type . "Messages"] = array();
    }
    $OBJECTS[$type . "Messages"][] = $message;
  }
  
  public static function addErrorMessage($message) {
    global $OBJECTS;
    
    $OBJECTS['error'] = true;
    UI::addMessage($message, "error");
  }
  
  public static function addSuccessMessage($message) {
    global $OBJECTS;
    
    $OBJECTS['success'] = true;
    UI::addMessage($message, "success");
  }
}
