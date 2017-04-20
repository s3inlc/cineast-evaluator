<?php

abstract class Handler {
  /**
   * @param $action string action type which should be handled
   */
  public abstract function handle($action);
}