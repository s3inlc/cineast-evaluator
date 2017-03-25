<?php
/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 22.03.17
 * Time: 17:35
 */
use DBA\JoinFilter;
use DBA\QueryFilter;
use DBA\QueryResultTuple;
use DBA\ResultTuple;

/** @var $OBJECTS array */

require_once(dirname(__FILE__) . "/inc/load.php");
if (!$LOGIN->isLoggedin()) {
  header("Location: index.php?err=4" . time());
  die();
}
$TEMPLATE = new Template("content/queries/index");

$MENU->setActive("queries");
$OBJECTS['pageTitle'] = "Cineast Evaluator";

if(isset($_POST['action'])){
  $queryHandler = new QueryHandler();
  $queryHandler->handle($_POST['action']);
}

if(isset($_GET['new'])){
  $TEMPLATE = new Template("content/queries/new");
}
else if(isset($_GET['view'])){
  $query = $FACTORIES::getQueryFactory()->get($_GET['view']);
  if($query != null){
    $TEMPLATE = new Template("content/queries/detail");
    $qF = new QueryFilter(QueryResultTuple::QUERY_ID, $query->getId(), "=");
    $jF = new JoinFilter($FACTORIES::getQueryResultTupleFactory(), ResultTuple::RESULT_TUPLE_ID, QueryResultTuple::RESULT_TUPLE_ID);
    $joinedResults = $FACTORIES::getResultTupleFactory()->filter(array($FACTORIES::FILTER => $qF, $FACTORIES::JOIN => $jF));
    $OBJECTS['results'] = $joinedResults['ResultTuple'];
    
    $mediaTypes = $FACTORIES::getMediaTypeFactory()->filter(array());
    $types = array();
    foreach($mediaTypes as $mediaType){
      $types[$mediaType->getId()] = $mediaType;
    }
    $OBJECTS['mediaTypes'] = $types;
  }
}

$queries = $FACTORIES::getQueryFactory()->filter(array());
$OBJECTS['queries'] = $queries;

echo $TEMPLATE->render($OBJECTS);