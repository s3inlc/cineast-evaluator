<?php
use DBA\QueryFilter;
use DBA\QueryResultTuple;
use DBA\TwoCompareAnswer;

class PruneHandler extends Handler {
  
  /**
   * @param string $answer
   */
  public function handle($answer) {
    global $FACTORIES;
    
    $answeredTuple = $FACTORIES::getResultTupleFactory()->get($_POST['resultTupleId']);
    if ($answeredTuple == null) {
      UI::addErrorMessage("Invalid submission!");
      return;
    }
    else if ($answer == AnswerType::COMPARE_TWO_SKIP) {
      // no action required
      $_SESSION['lastId'] = $answeredTuple->getId();
    }
    else {
      // an answer was given for this tuple -> mark it as final and save the answer
      $pruneSession = $FACTORIES::getAnswerSessionFactory()->get($_SESSION['pruneSessionId']);
      $twoCompareAnswer = new TwoCompareAnswer(0, time(), $answeredTuple->getId(), $answer, $pruneSession->getId());
      $FACTORIES::getTwoCompareAnswerFactory()->save($twoCompareAnswer);
      $answeredTuple->setIsFinal(1);
      $answeredTuple->setMu($answer);
      $answeredTuple->setSigma(0);
      $FACTORIES::getResultTupleFactory()->update($answeredTuple);
      
      // update the query progress
      $qF = new QueryFilter(QueryResultTuple::RESULT_TUPLE_ID, $answeredTuple->getId(), "=");
      $queryResults = $FACTORIES::getQueryResultTupleFactory()->filter(array($FACTORIES::FILTER => $qF));
      foreach ($queryResults as $queryResult) {
        Util::checkQueryUpdate($queryResult->getQueryId());
      }
      
      $_SESSION['lastId'] = $answeredTuple->getId();
    }
  }
}




