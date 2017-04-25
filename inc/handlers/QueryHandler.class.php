<?php
use DBA\MediaObject;
use DBA\Query;
use DBA\QueryResultTuple;
use DBA\ResultTuple;

class QueryHandler extends Handler {
  
  /**
   * @param $action string action type which should be handled
   */
  public function handle($action) {
    switch ($action) {
      case "addQuery":
        $this->addQuery($_FILES['file'], $_POST['queryName']);
        break;
      default:
        UI::addErrorMessage("Unknown action!");
        break;
    }
  }
  
  /**
   * @param $FILE array
   *   This array must contain following values:
   *   - "name" name of the file including the extension (.zip)
   *   - "tmp_name" the current location where it's saved
   *   - "error" should be 0 to avoid an error
   * @param $queryName string name of the query
   * @param bool $isUpload should be set to false if import is not from webinterface upload
   */
  public function addQuery($FILE, $queryName, $isUpload = true) {
    /** @var $LOGIN Login */
    global $FACTORIES, $LOGIN;
    
    ini_set("max_execution_time", "0");
    
    $path = STORAGE_PATH . TMP_FOLDER . "import-" . time() . "/";
    mkdir($path);
    $filename = $path . "import.zip";
    
    $queryName = htmlentities($queryName, false, "UTF-8");
    
    if ($FILE['error'] != 0) {
      UI::addErrorMessage("Error happened on file upload!");
      return;
    }
    else if (strpos($FILE['name'], ".zip") === false) {
      UI::addErrorMessage("File must be uploaded as .zip archive!");
      return;
    }
    
    if ($isUpload && !move_uploaded_file($FILE['tmp_name'], $filename)) {
      UI::addErrorMessage("Failed to move uploaded file into storage directory!");
      return;
    }
    else if (!$isUpload && !copy($FILE['tmp_name'], $filename)) {
      UI::addErrorMessage("Failed to move uploaded file into storage directory!");
      return;
    }
    // upload was successful
    // processing the .zip now
    copy($filename, STORAGE_PATH . QUERIES_FOLDER . "import-" . time() . ".zip");
    exec("cd '$path' && unzip '$filename'");
    
    // read meta file
    if (!file_exists($path . "meta.json")) {
      UI::addErrorMessage("Invalid packed uploaded: meta.json is missing!");
      return;
    }
    $meta = file_get_contents($path . "meta.json");
    $meta = json_decode($meta, true);
    if (!$meta) {
      UI::addErrorMessage("Invalid JSON in meta.json!");
      return;
    }
    else if (!isset($meta['queryObject'])) {
      UI::addErrorMessage("Invalid packed uploaded: queryObject is missing!");
      return;
    }
    else if (!isset($meta['resultSet'])) {
      UI::addErrorMessage("Invalid packed uploaded: resultSet is missing!");
      return;
    }
    else if (sizeof($meta['resultSet']) < 1) {
      UI::addErrorMessage("Invalid packed uploaded: resultSet is empty!");
      return;
    }
    $queryObject = $meta['queryObject'];
    $querySource = "";
    if (isset($meta['source'])) {
      $querySource = $meta['source'];
    }
    $resultSet = $meta['resultSet'];
    unset($meta['queryObject']);
    unset($meta['resultSet']);
    $queryMeta = json_encode($meta);
    
    // check if all files are present
    if (!file_exists($path . "data/" . $queryObject)) {
      UI::addErrorMessage("Invalid packed uploaded: queryObject not present!");
      return;
    }
    foreach ($resultSet as $result) {
      if (!isset($result['mediaObject']) || !isset($result['score']) || !isset($result['rank'])) {
        UI::addErrorMessage("Invalid packed uploaded: invalid result in resultSet!");
        return;
      }
      else if (!file_exists($path . "data/" . $result['mediaObject'])) {
        UI::addErrorMessage("Invalid packed uploaded: mediaObject '" . $result['mediaObject'] . "' not present!");
        return;
      }
    }
    
    // at this point everything is present and we can add it to the database
    
    $query = new Query(0, 0, time(), $queryName, $LOGIN->getUserId(), $queryMeta, 0, 0);
    $query = $FACTORIES::getQueryFactory()->save($query);
    
    $mediaType = Util::getMediaType($path . "data/" . $queryObject);
    $checksum = sha1_file($path . "data/" . $queryObject);
    $queryMediaObject = Util::getMediaObject($checksum);
    if ($queryMediaObject == null) {
      $mediaName = STORAGE_PATH . MEDIA_FOLDER . $checksum;
      copy($path . "data/" . $queryObject, $mediaName);
      $queryMediaObject = new MediaObject(0, $mediaType->getId(), $mediaName, time(), $checksum, $querySource);
      $queryMediaObject = $FACTORIES::getMediaObjectFactory()->save($queryMediaObject);
    }
    
    $queryResultTuples = array();
    foreach ($resultSet as $result) {
      $checksum = sha1_file($path . "data/" . $result['mediaObject']);
      $resultMediaObject = Util::getMediaObject($checksum);
      
      // check media object
      $mediaType = Util::getMediaType($path . "data/" . $result['mediaObject']);
      if ($resultMediaObject == null) {
        $mediaName = STORAGE_PATH . MEDIA_FOLDER . $checksum;
        copy($path . "data/" . $result['mediaObject'], $mediaName);
        if (!isset($result['source'])) {
          $result['source'] = "";
        }
        $resultMediaObject = new MediaObject(0, $mediaType->getId(), $mediaName, time(), $checksum, $result['source']);
        Util::resizeImage($mediaName);
        $resultMediaObject = $FACTORIES::getMediaObjectFactory()->save($resultMediaObject);
      }
      
      // check result tuple
      $resultTuple = Util::getResultTuple($queryMediaObject, $resultMediaObject);
      if ($resultTuple == null) {
        $resultTuple = new ResultTuple(0, $queryMediaObject->getId(), $resultMediaObject->getId(), -1, -1, 0);
        $resultTuple = $FACTORIES::getResultTupleFactory()->save($resultTuple);
      }
      
      // connect result tuple to query
      $queryResultTuple = new QueryResultTuple(0, $query->getId(), $resultTuple->getId(), $result['score'], $result['rank']);
      $queryResultTuples[] = $queryResultTuple;
    }
    $FACTORIES::getQueryResultTupleFactory()->massSave($queryResultTuples);
    
    
    // clean up
    system("rm -r '$path'");
  }
}





















