<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 23.03.17
 * Time: 11:58
 */
abstract class Handler {
  /**
   * @param $action string action type which should be handled
   */
  public abstract function handle($action);
}