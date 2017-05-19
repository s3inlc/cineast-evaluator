<?php
use DBA\AnswerSession;
use DBA\ContainFilter;
use DBA\JoinFilter;
use DBA\OrderFilter;
use DBA\Query;
use DBA\QueryFilter;
use DBA\QueryResultTuple;
use DBA\ResultTuple;
use DBA\TwoCompareAnswer;

class QuestionPool {
  private $pool = array();
  
  public function __construct() {
    // TODO: create pool
  }
  
  /**
   * @param $answerSession AnswerSession
   * @return SessionQuestion[]
   */
  public function getNextQuestionBlock($answerSession) {
    global $FACTORIES, $OBJECTS;
    
    $startTime = microtime(true);
    
    $this->pool = array();
    
    // load tuples which are not answered from this user yet
    $qF1 = new QueryFilter(AnswerSession::PLAYER_ID, $answerSession->getPlayerId(), "=");
    $qF2 = new QueryFilter(AnswerSession::MICROWORKER_ID, $answerSession->getMicroworkerId(), "=");
    $qF3 = new QueryFilter(AnswerSession::USER_ID, $answerSession->getUserId(), "=");
    $answerSessions = $FACTORIES::getAnswerSessionFactory()->filter(array($FACTORIES::FILTER => array($qF1, $qF2, $qF3)));
    $answerSessionIds = array();
    foreach ($answerSessions as $a) {
      if ($a->getMicroworkerId() == null && $a->getPlayerId() == null && $a->getUserId() == null) {
        continue;
      }
      $answerSessionIds[] = $a->getId();
    }
    
    $twoAnswers = array();
    if (sizeof($answerSessionIds) > 0) {
      $qF = new ContainFilter(TwoCompareAnswer::ANSWER_SESSION_ID, $answerSessionIds);
      $twoAnswers = $FACTORIES::getTwoCompareAnswerFactory()->filter(array($FACTORIES::FILTER => $qF));
    }
    
    $tupleIds = array();
    foreach ($twoAnswers as $twoAnswer) {
      $tupleIds[] = $twoAnswer->getResultTupleId();
    }
    
    $qF = new QueryFilter(Query::IS_CLOSED, 0, "=");
    $oF = new OrderFilter(Query::PRIORITY, "DESC");
    $queries = $FACTORIES::getQueryFactory()->filter(array($FACTORIES::FILTER => $qF, $FACTORIES::ORDER => $oF));
    
    $questions = array();
    $sessionSize = SESSION_SIZE_GAME;
    if ($answerSession->getMicroworkerId() != null) {
      $sessionSize = SESSION_SIZE_MICROWORKER;
    }
    
    for ($i = 0; $i < $sessionSize; $i++) {
      if (random_int(0, 100) < 15) {
        $question = Util::getSecurityQuestion();
        if ($question != null) {
          $questions[] = $question;
          continue;
        }
      }
      
      $query = Util::getQueryWeightedWithPriority($queries); // get a random query weighed by the priority
      
      $qF1 = new QueryFilter(QueryResultTuple::QUERY_ID, $query->getId(), "=", $FACTORIES::getQueryResultTupleFactory());
      $qF2 = new QueryFilter(ResultTuple::IS_FINAL, "0", "=");
      $jF = new JoinFilter($FACTORIES::getQueryResultTupleFactory(), ResultTuple::RESULT_TUPLE_ID, QueryResultTuple::RESULT_TUPLE_ID);
      $oF = new OrderFilter(QueryResultTuple::RANK, "ASC", $FACTORIES::getQueryResultTupleFactory());
      $joined = $FACTORIES::getResultTupleFactory()->filter(array($FACTORIES::FILTER => array($qF1, $qF2), $FACTORIES::ORDER => $oF, $FACTORIES::JOIN => $jF));
      
      if (sizeof($joined[$FACTORIES::getResultTupleFactory()->getModelName()]) == 0) {
        continue;
      }
      
      /** @var $tuple ResultTuple */
      $found = false;
      while (!$found) {
        $tuple = Util::getTupleWeightedWithRankAndSigma(
          $joined[$FACTORIES::getResultTupleFactory()->getModelName()],
          $joined[$FACTORIES::getQueryResultTupleFactory()->getModelName()],
          $tupleIds
        );
        if ($tuple == null) {
          break; // we have no tuples left on this query
        }
        
        // test if the result tuple is finished
        $qF = new QueryFilter(TwoCompareAnswer::RESULT_TUPLE_ID, $tuple->getId(), "=");
        $count = $FACTORIES::getTwoCompareAnswerFactory()->countFilter(array($FACTORIES::FILTER => $qF));
        // filter out completed tuples
        if ($count < RESULT_TUPLE_EVALUATED_ANSWERS_THRESHOLD || $tuple->getSigma() > RESULT_TUPLE_EVALUATED_SIGMA_THRESHOLD) {
          $found = true;
        }
        else{
          $tupleIds[] = $tuple->getId();
        }
      }
      
      if (!$found) {
        continue;
      }
      
      $mediaObjects = array($FACTORIES::getMediaObjectFactory()->get($tuple->getObjectId1()), $FACTORIES::getMediaObjectFactory()->get($tuple->getObjectId2()));
      $questions[] = new SessionQuestion(SessionQuestion::TYPE_COMPARE_TWO, $mediaObjects, array($tuple));
    }
    
    $endTime = microtime(true);
    $OBJECTS['loadTime'] = $endTime - $startTime;
    return $questions;
  }
}