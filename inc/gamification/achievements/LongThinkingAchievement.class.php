<?php
use DBA\AnswerSession;
use DBA\Game;
use DBA\JoinFilter;
use DBA\Player;
use DBA\QueryFilter;
use DBA\TwoCompareAnswer;

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 25.04.17
 * Time: 15:31
 */
class LongThinkingAchievement extends GameAchievement {
  
  /**
   * @return string
   */
  function getAchievementName() {
    return "Heavy Thinking";
  }
  
  function getIsHidden() {
    return true;
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
    
    // this achievement is reached when the player required more than 30 seconds to answer a question
    $qF = new QueryFilter(Game::PLAYER_ID, $player->getId(), "=");
    $jF = new JoinFilter($FACTORIES::getAnswerSessionFactory(), Game::ANSWER_SESSION_ID, AnswerSession::ANSWER_SESSION_ID);
    $joined = $FACTORIES::getGameFactory()->filter(array($FACTORIES::FILTER => $qF, $FACTORIES::JOIN => $jF));
    $answerSessions = $joined[$FACTORIES::getAnswerSessionFactory()->getModelName()];
    foreach ($answerSessions as $answerSession) {
      /** @var $answerSession AnswerSession */
      $qF = new QueryFilter(TwoCompareAnswer::ANSWER_SESSION_ID, $answerSession->getId(), "=");
      $answers = $FACTORIES::getTwoCompareAnswerFactory()->filter(array($FACTORIES::FILTER => $qF));
      /** @var $last TwoCompareAnswer */
      $last = null;
      foreach ($answers as $answer) {
        if ($last == null) {
          $last = $answer;
          continue;
        }
        $delta = $answer->getTime() - $last->getTime();
        if ($delta >= 30) {
          return true;
        }
      }
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
    return "longThinking";
  }
  
  /**
   * @return string
   */
  function getDescription() {
    return "Think more than 30 seconds on an question<br>Gives 5% extra score";
  }
}