<?php
use DBA\AnswerSession;
use DBA\OrderFilter;
use DBA\QueryFilter;
use DBA\ThreeCompareAnswer;
use DBA\TwoCompareAnswer;

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 29.03.17
 * Time: 16:17
 */
class UserSession {
  /** @var $answerSession AnswerSession */
  private $answerSession = null;
  
  /** @var SessionQuestion[] */
  private $questionQueue = null;
  
  public static $OPEN_SESSION_TIMEOUT        = 24 * 3600;
  public static $MICROWORKER_SESSION_TIMEOUT = 3600;
  
  public function __construct() {
    global $FACTORIES;
    
    // start session
    session_start();
    $sessionType = new SessionType();
    
    // search for existing session and check it if present
    if (isset($_SESSION['answerSessionId'])) {
      $this->answerSession = $FACTORIES::getAnswerSessionFactory()->get($_SESSION['answerSessionId']);
      if ($this->answerSession->getIsOpen() == 0) {
        $this->answerSession = null;
      }
      else if(sizeof(unserialize($_SESSION['questions'])) == 0){
        // no more questions available, so we close the session
        $this->answerSession->setIsOpen(0);
        $FACTORIES::getAnswerSessionFactory()->update($this->answerSession);
        $this->answerSession = null;
      }
      else if ($this->answerSession->getMicroworkerId() != null) {
        // TODO: check if microworker session is still open and valid
      }
      else {
        // TODO: Test if session type is still valid
      }
      
      if ($this->answerSession != null) {
        // test if last answer was too long ago, so we should create a new session
        $lastAnswer = 0;
        $qF = new QueryFilter(TwoCompareAnswer::ANSWER_SESSION_ID, $this->answerSession->getId(), "=");
        $oF = new OrderFilter(TwoCompareAnswer::TIME, "DESC LIMIT 1");
        $last = $FACTORIES::getTwoCompareAnswerFactory()->filter(array($FACTORIES::FILTER => $qF, $FACTORIES::ORDER => $oF), 1);
        if ($last != null && $last->getTime() > $lastAnswer) {
          $lastAnswer = $last->getTime();
        }
        $qF = new QueryFilter(ThreeCompareAnswer::ANSWER_SESSION_ID, $this->answerSession->getId(), "=");
        $oF = new OrderFilter(ThreeCompareAnswer::TIME, "DESC LIMIT 1");
        $last = $FACTORIES::getThreeCompareAnswerFactory()->filter(array($FACTORIES::FILTER => $qF, $FACTORIES::ORDER => $oF), 1);
        if ($last != null && $last->getTime() > $lastAnswer) {
          $lastAnswer = $last->getTime();
        }
        
        if (time() - $lastAnswer > UserSession::$OPEN_SESSION_TIMEOUT && $this->answerSession->getPlayerId() != null) {
          $this->close();
        }
        else if (time() - $lastAnswer > UserSession::$MICROWORKER_SESSION_TIMEOUT && $this->answerSession->getMicroworkerId() != null) {
          $this->close();
        }
      }
      
      if($this->answerSession != null) {
        // reload questions if they were already created earlier
        $this->questionQueue = new QuestionQueue(unserialize($_SESSION['questions']));
      }
    }
    
    // get info about what session type it "should" be
    $playerId = null;
    $microworkerId = null;
    $userId = null;
    switch ($sessionType->getType()) {
      case SessionType::SESSION_TYPE_USER:
        $userId = $sessionType->getId();
        break;
      case SessionType::SESSION_TYPE_PLAYER:
        $playerId = $sessionType->getId();
        break;
      case SessionType::SESSION_TYPE_MICROWORKER:
        $microworkerId = $sessionType->getId();
        break;
    }
    
    // create new session if required
    if ($this->answerSession == null) {
      $this->createNewSession($microworkerId, $userId, $playerId);
    }
    else {
      // if session exists, check if we can identify it now
      if ($userId != null && $this->isUnknownUser()) {
        // -> player authenticated now, but was not authenticated when the session started
        $this->answerSession->setUserId($userId);
        $FACTORIES::getAnswerSessionFactory()->update($this->answerSession);
      }
      else if ($playerId != null && $this->isUnknownUser()) {
        // -> player authenticated now, but was not authenticated when the session started
        $FACTORIES::getAnswerSessionFactory()->update($this->answerSession);
      }
      // TODO: I think it's not a good idea to later assign sessions to microworkers
    }
    
    // save answerSessionId in session
    $_SESSION['answerSessionId'] = $this->answerSession->getId();
  }
  
  /**
   * @return bool returns true if the session has no related user, microworker and player set
   */
  private function isUnknownUser() {
    return $this->answerSession->getUserId() == null && $this->answerSession->getMicroworkerId() == null && $this->answerSession->getPlayerId() == null;
  }
  
  private function createNewSession($microworkerId, $userId, $playerId) {
    global $FACTORIES;
    
    $this->answerSession = new AnswerSession(0, $microworkerId, $userId, $playerId, 0.5, 1, time(), Util::getIP(), Util::getUserAgentHeader());
    $this->answerSession = $FACTORIES::getAnswerSessionFactory()->save($this->answerSession);
  
    // init question pool and get questions block
    $questionPool = new QuestionPool();
    $questions = $questionPool->getNextQuestionBlock($this->answerSession);
    
    // this needs to be tested, if serialization works without problems
    $this->questionQueue = new QuestionQueue($questions);
    $_SESSION['questions'] = serialize($questions);
  }
  
  public function getAnswerSession() {
    return $this->answerSession;
  }
  
  public function getNextQuestion() {
    // TODO: here we update the session quality and we decide if we need to do a security question or not
    if(!$this->questionQueue->questionAvailable()){
      return null;
    }
    
    $_SESSION['isSecurityQuestion'] = false;
    return $this->questionQueue->getFirst();
  }
  
  public function answerQuestion() {
    global $FACTORIES;
    
    $validator = new SessionValidator($this->answerSession);
    $errorType = ErrorType::NOTHING;
    
    // TODO: here the answer gets processed and checked
    if($_SESSION['isSecurityQuestion']){
      // handle this special
      die("SECURITY QUESTION");
    }
    else{
      // handle normal question
      $question = $this->questionQueue->getFirst();
      if($question->getQuestionType() == SessionQuestion::TYPE_COMPARE_TWO){
        // is acompare2 question
        $objectId1 = $_POST['objectId1'];
        $objectId2 = $_POST['objectId2'];
        $answer = intval($_POST['answer']);
        if(!in_array($answer, array(AnswerType::COMPARE_TWO_NO_SIMILARITY, AnswerType::COMPARE_TWO_SLIGHTLY_SIMILAR, AnswerType::COMPARE_TWO_VERY_SIMILAR, AnswerType::COMPARE_TWO_NEARLY_IDENTICAL))){
          // TODO: handle error
          die("INVALID ANSWER");
        }
        else if($objectId1 != $question->getMediaObjects()[0]->getId() || $objectId2 != $question->getMediaObjects()[1]->getId()){
          // TODO: handle error
          die("NOT MATCHING QUESTION");
        }
        else{
          // answer matches the current question
          $twoCompareAnswer = new TwoCompareAnswer(0, time(), $question->getResultTuples()[0]->getId(), $answer, $this->answerSession->getId());
          $FACTORIES::getTwoCompareAnswerFactory()->save($twoCompareAnswer);
          $this->questionQueue->pop();
          $_SESSION['questions'] = serialize($this->questionQueue->getQuestions());
          $errorType = ErrorType::NO_ERROR;
        }
      }
      else if($question->getQuestionType() == SessionQuestion::TYPE_COMPARE_TRIPLE){
        // is a compare3 question
        die("COMPARE3");
      }
      else{
        // TODO: strange error, decide what's happening here
        die("WRONG QUESTION TYPE");
      }
    }
    $this->answerSession->setCurrentValidity($validator->update($errorType));
    $FACTORIES::getAnswerSessionFactory()->update($this->answerSession);
    $_SESSION['isSecurityQuestion'] = false;
  }
  
  public function close() {
    global $FACTORIES;
    
    if ($this->answerSession != null) {
      $this->answerSession->setIsOpen(0);
      $FACTORIES::getAnswerSessionFactory()->update($this->answerSession);
      $this->answerSession = null;
    }
  }
}