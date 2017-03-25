<?php

/**
 * Created by IntelliJ IDEA.
 * User: sein
 * Date: 02.01.17
 * Time: 23:57
 */

namespace DBA;

class MediaType extends AbstractModel {
  private $mediaTypeId;
  private $typeName;
  private $extension;
  private $template;
  
  function __construct($mediaTypeId, $typeName, $extension, $template) {
    $this->mediaTypeId = $mediaTypeId;
    $this->typeName = $typeName;
    $this->extension = $extension;
    $this->template = $template;
  }
  
  function getKeyValueDict() {
    $dict = array();
    $dict['mediaTypeId'] = $this->mediaTypeId;
    $dict['typeName'] = $this->typeName;
    $dict['extension'] = $this->extension;
    $dict['template'] = $this->template;
    
    return $dict;
  }
  
  function getPrimaryKey() {
    return "mediaTypeId";
  }
  
  function getPrimaryKeyValue() {
    return $this->mediaTypeId;
  }
  
  function getId() {
    return $this->mediaTypeId;
  }
  
  function setId($id) {
    $this->mediaTypeId = $id;
  }
  
  function getTypeName(){
    return $this->typeName;
  }
  
  function setTypeName($typeName){
    $this->typeName = $typeName;
  }
  
  function getExtension(){
    return $this->extension;
  }
  
  function setExtension($extension){
    $this->extension = $extension;
  }
  
  function getTemplate(){
    return $this->template;
  }
  
  function setTemplate($template){
    $this->template = $template;
  }

  const MEDIA_TYPE_ID = "mediaTypeId";
  const TYPE_NAME = "typeName";
  const EXTENSION = "extension";
  const TEMPLATE = "template";
}
