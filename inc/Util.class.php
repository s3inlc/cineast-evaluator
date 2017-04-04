<?php
use DBA\JoinFilter;
use DBA\MediaObject;
use DBA\MediaType;
use DBA\QueryFilter;
use DBA\ResultTuple;

/**
 *
 * @author Sein
 *
 *         Bunch of useful static functions.
 */
class Util {
  /**
   * Calculates variable. Used in Templates.
   * @param $in mixed calculation to be done
   * @return mixed
   */
  public static function calculate($in) {
    return $in;
  }
  
  public static function checkFolders() {
    $tempDir = STORAGE_PATH . TMP_FOLDER;
    if (!file_exists($tempDir)) {
      mkdir($tempDir);
    }
    $queryDir = STORAGE_PATH . QUERIES_FOLDER;
    if (!file_exists($queryDir)) {
      mkdir($queryDir);
    }
    $mediaDir = STORAGE_PATH . MEDIA_FOLDER;
    if (!file_exists($mediaDir)) {
      mkdir($mediaDir);
    }
  }
  
  /**
   * Converts a given string to hex code.
   *
   * @param string $string
   *          string to convert
   * @return string converted string into hex
   */
  public static function strToHex($string) {
    return implode(unpack("H*", $string));
  }
  
  public static function getExtension($file) {
    $basename = explode(".", basename($file));
    return strtolower($basename[sizeof($basename) - 1]);
  }
  
  /**
   * @param $object1 MediaObject
   * @param $object2 MediaObject
   */
  public static function getResultTuple($object1, $object2) {
    global $FACTORIES;
    
    $qF1 = new QueryFilter(ResultTuple::OBJECT_ID1, $object1->getId(), "=");
    $qF2 = new QueryFilter(ResultTuple::OBJECT_ID2, $object2->getId(), "=");
    return $FACTORIES::getResultTupleFactory()->filter(array($FACTORIES::FILTER => array($qF1, $qF2)), true);
  }
  
  public static function getMediaObject($checksum) {
    global $FACTORIES;
    
    $qF = new QueryFilter(MediaObject::CHECKSUM, $checksum, "=");
    return $FACTORIES::getMediaObjectFactory()->filter(array($FACTORIES::FILTER => $qF), true);
  }
  
  public static function getMediaType($file) {
    global $FACTORIES;
    
    $extension = Util::getExtension($file);
    
    $qF = new QueryFilter(MediaType::EXTENSION, $extension, "=");
    $mediaType = $FACTORIES::getMediaTypeFactory()->filter(array($FACTORIES::FILTER => $qF), true);
    if ($mediaType == null) {
      // create this new media type
      $mediaType = new MediaType(0, $extension, $extension, null);
      $mediaType = $FACTORIES::getMediaTypeFactory()->save($mediaType);
    }
    return $mediaType;
  }
  
  /**
   * @param $mediaObjectId int
   * @return string
   */
  public static function getMediaTypeNameForObject($mediaObjectId) {
    global $FACTORIES;
    
    $qF = new QueryFilter(MediaObject::MEDIA_OBJECT_ID, $mediaObjectId, "=");
    $jF = new JoinFilter($FACTORIES::getMediaTypeFactory(), MediaObject::MEDIA_TYPE_ID, MediaType::MEDIA_TYPE_ID);
    $joined = $FACTORIES::getMediaObjectFactory()->filter(array($FACTORIES::FILTER => $qF, $FACTORIES::JOIN => $jF));
    if (sizeof($joined['MediaType']) == 0) {
      return "unknown";
    }
    /** @var MediaType $mediaType */
    $mediaType = $joined['MediaType'][0];
    return $mediaType->getTypeName();
  }
  
  /**
   * This filesize is able to determine the file size of a given file, also if it's bigger than 4GB which causes
   * some problems with the built-in filesize() function of PHP.
   * @param $file string Filepath you want to get the size from
   * @return int -1 if the file doesn't exist, else filesize
   */
  public static function filesize($file) {
    if (!file_exists($file)) {
      return -1;
    }
    $fp = fopen($file, "rb");
    $pos = 0;
    $size = 1073741824;
    fseek($fp, 0, SEEK_SET);
    while ($size > 1) {
      fseek($fp, $size, SEEK_CUR);
      
      if (fgetc($fp) === false) {
        fseek($fp, -$size, SEEK_CUR);
        $size = (int)($size / 2);
      }
      else {
        fseek($fp, -1, SEEK_CUR);
        $pos += $size;
      }
    }
    
    while (fgetc($fp) !== false) {
      $pos++;
    }
    
    return $pos;
  }
  
  public static function resizeImage($path) {
    $size = getimagesize($path);
    if($size[0] <= IMAGE_MAX_WIDTH && $size[1] <= IMAGE_MAX_HEIGHT){
      return; // we don't need to do a resize here
    }
    $ratio = $size[0] / $size[1]; // width/height
    if ($ratio > 1) {
      $width = IMAGE_MAX_WIDTH;
      $height = IMAGE_MAX_HEIGHT / $ratio;
    }
    else {
      $width = IMAGE_MAX_WIDTH * $ratio;
      $height = IMAGE_MAX_HEIGHT;
    }
    $src = imagecreatefromstring(file_get_contents($path));
    $dst = imagecreatetruecolor($width, $height);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
    imagedestroy($src);
    imagepng($dst, $path); // adjust format as needed
    imagedestroy($dst);
  }
  
  /**
   * Tries to determine the IP of the client.
   * @return string 0.0.0.0 or the client IP
   */
  public static function getIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else {
      $ip = $_SERVER['REMOTE_ADDR'];
    }
    if (!$ip) {
      return "0.0.0.0";
    }
    return $ip;
  }
  
  /**
   * Returns the username from the given userId
   * @param $id int ID for the user
   * @return string username or unknown-id
   */
  public static function getUsernameById($id) {
    global $FACTORIES;
    
    $user = $FACTORIES::getUserFactory()->get($id);
    if ($user === null) {
      return "Unknown-$id";
    }
    return $user->getUsername();
  }
  
  public static function getUserAgentHeader() {
    return json_encode(getallheaders());
  }
  
  /**
   * Cut a string to a certain number of letters. If the string is too long, instead replaces the last three letters with ...
   * @param $string String you want to short
   * @param $length Number of Elements you want the string to have
   * @return string Formatted string
   */
  public static function shortenstring($string, $length) {
    // shorten string that would be too long
    $return = "<span title='$string'>";
    if (strlen($string) > $length) {
      $return .= substr($string, 0, $length - 3) . "...";
    }
    else {
      $return .= $string;
    }
    $return .= "</span>";
    return $return;
  }
  
  /**
   * This sends a given email with text and subject to the address.
   *
   * @param string $address
   *          email address of the receiver
   * @param string $subject
   *          subject of the email
   * @param string $text
   *          html content of the email
   * @return true on success, false on failure
   */
  public static function sendMail($address, $subject, $text) {
    //TODO: make sending email configurable
    
    $header = "Content-type: text/html; charset=utf8\r\n";
    $header .= "From: todo <todo@to.do>\r\n";
    if (!mail($address, $subject, $text, $header)) {
      return false;
    }
    return true;
  }
  
  /**
   * Generates a random string with mixedalphanumeric chars
   *
   * @param int $length
   *          length of random string to generate
   * @return string random string
   */
  public static function randomString($length) {
    $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $result = "";
    for ($x = 0; $x < $length; $x++) {
      $result .= $charset[mt_rand(0, strlen($charset) - 1)];
    }
    return $result;
  }
}
