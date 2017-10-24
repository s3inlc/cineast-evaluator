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
  private $source;
  private $original;
  
  function __construct($mediaObjectId, $mediaTypeId, $filename, $time, $checksum, $source, $original) {
    $this->mediaObjectId = $mediaObjectId;
    $this->mediaTypeId = $mediaTypeId;
    $this->filename = $filename;
    $this->time = $time;
    $this->checksum = $checksum;
    $this->source = $source;
    $this->original = $original;
  }
  
  function getKeyValueDict() {
    $dict = array();
    $dict['mediaObjectId'] = $this->mediaObjectId;
    $dict['mediaTypeId'] = $this->mediaTypeId;
    $dict['filename'] = $this->filename;
    $dict['time'] = $this->time;
    $dict['checksum'] = $this->checksum;
    $dict['source'] = $this->source;
    $dict['original'] = $this->original;
    
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
  
  function getSource(){
    return $this->source;
  }
  
  function setSource($source){
    $this->source = $source;
  }
  
  function getOriginal(){
    return $this->original;
  }
  
  function setOriginal($original){
    $this->original = $original;
  }

  const MEDIA_OBJECT_ID = "mediaObjectId";
  const MEDIA_TYPE_ID = "mediaTypeId";
  const FILENAME = "filename";
  const TIME = "time";
  const CHECKSUM = "checksum";
  const SOURCE = "source";
  const ORIGINAL = "original";
}
