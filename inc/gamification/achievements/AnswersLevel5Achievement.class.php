<?php
use DBA\Game;
use DBA\Player;
use DBA\QueryFilter;
use DBA\TwoCompareAnswer;

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 25.04.17
 * Time: 15:31
 */
class AnswersLevel5Achievement extends GameAchievement {
  
  /**
   * @return string
   */
  function getAchievementName() {
    return "Answered Questions Level 5";
  }
  
  /**
   * @param $player Player
   * @return bool
   */
  function isReachedByPlayer($player) {
    global $FACTORIES;
    
    if ($player == null || $this->alreadyReached($player)) {
      return false;
    }
    
    // this achievement is reached when the user answered 2000 questions
    $qF = new QueryFilter(Game::PLAYER_ID, $player->getId(), "=");
    $answerSessions = $FACTORIES::getAnswerSessionFactory()->filter(array($FACTORIES::FILTER => $qF));
    $total = 0;
    foreach ($answerSessions as $answerSession) {
      $qF = new QueryFilter(TwoCompareAnswer::ANSWER_SESSION_ID, $answerSession->getId(), "=");
      $total += $FACTORIES::getTwoCompareAnswerFactory()->countFilter(array($FACTORIES::FILTER => $qF));
    }
    
    if ($total >= 2000) {
      return true;
    }
    return false;
  }
  
  /**
   * @return string
   */
  function getAchievementImage() {
    return "success.png"; // TODO: add image
  }
  
  /**
   * @return float
   */
  function getMultiplicatorGain() {
    return 1.1;
  }
  
  /**
   * @return string
   */
  function getIdentifier() {
    return "answersLevel5";
  }
  
  /**
   * @return string
   */
  function getDescription() {
    return "Answer at least 2000 questions.<br>Gives 10% extra score";
  }
}