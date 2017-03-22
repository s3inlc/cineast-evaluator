<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 02.01.17
 * Time: 23:57
 */

namespace DBA;

class MediaObject extends AbstractModel {
  private $mediaObjectId;
  private $mediaTypeId;
  private $filename;
  private $time;
  private $checksum;
  
  function __construct($mediaObjectId, $mediaTypeId, $filename, $time, $checksum) {
    $this->mediaObjectId = $mediaObjectId;
    $this->mediaTypeId = $mediaTypeId;
    $this->filename = $filename;
    $this->time = $time;
    $this->checksum = $checksum;
  }
  
  function getKeyValueDict() {
    $dict = array();
    $dict['mediaObjectId'] = $this->mediaObjectId;
    $dict['mediaTypeId'] = $this->mediaTypeId;
    $dict['filename'] = $this->filename;
    $dict['time'] = $this->time;
    $dict['checksum'] = $this->checksum;
    
    return $dict;
  }
  
  function getPrimaryKey() {
    return "mediaObjectId";
  }
  
  function getPrimaryKeyValue() {
    return $this->mediaObjectId;
  }
  
  function getId() {
    return $this->mediaObjectId;
  }
  
  function setId($id) {
    $this->mediaObjectId = $id;
  }
  
  function getMediaTypeId(){
    return $this->mediaTypeId;
  }
  
  function setMediaTypeId($mediaTypeId){
    $this->mediaTypeId = $mediaTypeId;
  }
  
  function getFilename(){
    return $this->filename;
  }
  
  function setFilename($filename){
    $this->filename = $filename;
  }
  
  function getTime(){
    return $this->time;
  }
  
  function setTime($time){
    $this->time = $time;
  }
  
  function getChecksum(){
    return $this->checksum;
  }
  
  function setChecksum($checksum){
    $this->checksum = $checksum;
  }

  const MEDIA_OBJECT_ID = "mediaObjectId";
  const MEDIA_TYPE_ID = "mediaTypeId";
  const FILENAME = "filename";
  const TIME = "time";
  const CHECKSUM = "checksum";
}
