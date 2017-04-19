<?php
use DBA\TwoCompareAnswer;

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 18.04.17
 * Time: 16:57
 */
class PruneHandler extends Handler {
  
  /**
   * @param $action string action type which should be handled
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
    else{
      $pruneSession = $FACTORIES::getAnswerSessionFactory()->get($_SESSION['pruneSessionId']);
      $twoCompareAnswer = new TwoCompareAnswer(0, time(), $answeredTuple->getId(), $answer, $pruneSession->getId());
      $FACTORIES::getTwoCompareAnswerFactory()->save($twoCompareAnswer);
      $answeredTuple->setIsFinal(1);
      $answeredTuple->setMu($answer);
      $answeredTuple->setSigma(0);
      $FACTORIES::getResultTupleFactory()->update($answeredTuple);
      $_SESSION['lastId'] = $answeredTuple->getId();
    }
  }
}




