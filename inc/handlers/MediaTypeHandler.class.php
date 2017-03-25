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
      default:
        UI::addErrorMessage("Unknown action!");
        break;
    }
  }
}