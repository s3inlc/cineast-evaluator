<?php

$CONF = array();

// Configure the required models here
$CONF['User'] = array(
  'userId',
  'username',
  'email',
  'passwordHash',
  'passwordSalt',
  'isValid',
  'isComputedPassword',
  'lastLoginDate',
  'registeredSince',
  'sessionLifetime'
);
$CONF['Session'] = array(
  'sessionId',
  'userId',
  'sessionStartDate',
  'lastActionDate',
  'isOpen',
  'sessionLifetime',
  'sessionKey'
);
$CONF['Query'] = array(
  'queryId',
  'isClosed',
  'time',
  'displayName',
  'userId',
  'meta',
  'priority'
);
$CONF['ResultTuple'] = array(
  'resultTupleId',
  'objectId1',
  'objectId2',
  'similarity',
  'certainty'
);
$CONF['QueryResultTuple'] = array(
  'queryResultTupleId',
  'queryId',
  'resultTupleId',
  'score',
  'rank'
);
$CONF['MediaObject'] = array(
  'mediaObjectId',
  'mediaTypeId',
  'filename',
  'time',
  'checksum'
);
$CONF['MediaType'] = array(
  'mediaTypeId',
  'typeName',
  'extension',
  'template'
);
$CONF['AnswerSession'] = array(
  'answerSessionId',
  'microworkerId',
  'userId',
  'playerId',
  'currentValidity',
  'isOpen',
  'timeOpened',
  'userAgentIp',
  'userAgentHeader'
);
$CONF['Player'] = array(
  'playerId',
  'playerName',
  'firstLogin',
  'lastLogin'
);
$CONF['ThreeCompareAnswer'] = array(
  'threeCompareAnswerId',
  'time',
  'answer',
  'resultTupleId1',
  'resultTupleId2',
  'answerSessionId'
);
$CONF['TwoCompareAnswer'] = array(
  'twoCompareAnswerId',
  'time',
  'resultTupleId',
  'answer',
  'answerSessionId'
);

foreach ($CONF as $NAME => $COLUMNS) {
  $class = file_get_contents(dirname(__FILE__) . "/AbstractModel.template.txt");
  $class = str_replace("__MODEL_NAME__", $NAME, $class);
  $vars = array();
  $init = array();
  $keyVal = array();
  $class = str_replace("__MODEL_PK__", $COLUMNS[0], $class);
  $functions = array();
  $params = array();
  $variables = array();
  foreach ($COLUMNS as $col) {
    if (sizeof($vars) > 0) {
      $getter = "function get" . strtoupper($col[0]) . substr($col, 1) . "(){\n    return \$this->$col;\n  }";
      $setter = "function set" . strtoupper($col[0]) . substr($col, 1) . "(\$$col){\n    \$this->$col = \$$col;\n  }";
      $functions[] = $getter;
      $functions[] = $setter;
    }
    $params[] = "\$$col";
    $vars[] = "private \$$col;";
    $init[] = "\$this->$col = \$$col;";
    $keyVal[] = "\$dict['$col'] = \$this->$col;";
    $variables[] = "const " . makeConstant($col) . " = \"$col\";";
    
  }
  $class = str_replace("__MODEL_PARAMS__", implode(", ", $params), $class);
  $class = str_replace("__MODEL_VARS__", implode("\n  ", $vars), $class);
  $class = str_replace("__MODEL_PARAMS_INIT__", implode("\n    ", $init), $class);
  $class = str_replace("__MODEL_KEY_VAL__", implode("\n    ", $keyVal), $class);
  $class = str_replace("__MODEL_GETTER_SETTER__", implode("\n  \n  ", $functions), $class);
  $class = str_replace("__MODEL_VARIABLE_NAMES__", implode("\n  ", $variables), $class);
  
  if (!file_exists(dirname(__FILE__) . "/" . $NAME . ".class.php")) {
    file_put_contents(dirname(__FILE__) . "/" . $NAME . ".class.php", $class);
  }
  
  $class = file_get_contents(dirname(__FILE__) . "/AbstractModelFactory.template.txt");
  $class = str_replace("__MODEL_NAME__", $NAME, $class);
  $dict = array();
  $dict2 = array();
  foreach ($COLUMNS as $col) {
    if (sizeof($dict) == 0) {
      $dict[] = "-1";
      $dict2[] = "\$pk";
    }
    else {
      $dict[] = "null";
      $dict2[] = "\$dict['$col']";
    }
  }
  $class = str_replace("__MODEL_DICT__", implode(", ", $dict), $class);
  $class = str_replace("__MODEL__DICT2__", implode(", ", $dict2), $class);
  
  if (!file_exists(dirname(__FILE__) . "/" . $NAME . "Factory.class.php")) {
    file_put_contents(dirname(__FILE__) . "/" . $NAME . "Factory.class.php", $class);
  }
}

$class = file_get_contents(dirname(__FILE__) . "/Factory.template.txt");
$static = array();
$functions = array();
foreach ($CONF as $NAME => $COLUMNS) {
  $lowerName = strtolower($NAME[0]) . substr($NAME, 1);
  $static[] = "private static \$" . $lowerName . "Factory = null;";
  $functions[] = "public static function get" . $NAME . "Factory() {\n    if (self::\$" . $lowerName . "Factory == null) {\n      self::\$" . $lowerName . "Factory = new " . $NAME . "Factory();\n    }\n    return self::\$" . $lowerName . "Factory;\n  }";
}
$class = str_replace("__MODEL_STATIC__", implode("\n  ", $static), $class);
$class = str_replace("__MODEL_FUNCTIONS__", implode("\n  \n  ", $functions), $class);

file_put_contents(dirname(__FILE__) . "/../Factory.class.php", $class);


function makeConstant($name) {
  $output = "";
  for ($i = 0; $i < strlen($name); $i++) {
    if ($name[$i] == strtoupper($name[$i]) && $i < strlen($name) - 1) {
      $output .= "_";
    }
    $output .= strtoupper($name[$i]);
  }
  return $output;
}

