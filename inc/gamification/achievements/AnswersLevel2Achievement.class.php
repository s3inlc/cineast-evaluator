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
class AnswersLevel2Achievement extends GameAchievement {
  
  /**
   * @return string
   */
  function getAchievementName() {
    return "Answered Questions Level 2";
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
    
    // this achievement is reached when the user answered 200 questions
    $qF = new QueryFilter(Game::PLAYER_ID, $player->getId(), "=");
    $answerSessions = $FACTORIES::getAnswerSessionFactory()->filter(array($FACTORIES::FILTER => $qF));
    $total = 0;
    foreach ($answerSessions as $answerSession) {
      $qF = new QueryFilter(TwoCompareAnswer::ANSWER_SESSION_ID, $answerSession->getId(), "=");
      $total += $FACTORIES::getTwoCompareAnswerFactory()->countFilter(array($FACTORIES::FILTER => $qF));
    }
    
    if ($total >= 200) {
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
    return 1.05;
  }
  
  /**
   * @return string
   */
  function getIdentifier() {
    return "answersLevel2";
  }
  
  /**
   * @return string
   */
  function getDescription() {
    return "Answer at least 200 questions.<br>Gives 5% extra score";
  }
}