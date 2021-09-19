<?php

namespace App\Core;

class Model {

  protected $db;
  protected $sql;
  protected $lastInsertId = 0;
  protected $errorInfo;
  protected $errorCode;

  public function __construct() {
    $this->db = \App\Core\Database::getPDO();
  }

  protected function setSql($sql) {
    $this->sql = $sql;
  }

  public function getSql() {
    return $this->sql;
  }

  protected function setErrorCode($errorCode) {
    $this->errorCode = $errorCode;
  }

  public function getErrorCode() {
    return $this->errorCode;
  }

  protected function setErrorInfo($errorInfo) {
    $this->errorInfo = $errorInfo;
  }

  public function getErrorInfo() {
    return $this->errorInfo;
  }

  protected function setLastInsertId($id) {
    $this->lastInsertId = $id;
  }

  public function getLastInsertId() {
    return $this->lastInsertId;
  }

}

?>