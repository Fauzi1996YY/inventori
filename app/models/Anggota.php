<?php

namespace App\Model;

class Anggota extends \App\Core\Model {

  public function getAllData() {
    
    $sql = 'select *
            from `anggota`
            order by `id_anggota`
            ';
    
    $this->setSql($sql);
    $stmt = $this->db->prepare($sql);
    
    try {
      $stmt->execute();
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    } catch(\PDOException $e) {
      $this->setErrorInfo($e->getMessage());
      $this->setErrorCode($e->getCode());
      return false;
    }
  }

  public function getPaginatedData($limit) {
    
    $sql = 'select *
            from `anggota`
            order by `id_anggota`
            limit ' . $limit;
    
    $this->setSql($sql);
    $stmt = $this->db->prepare($sql);
    
    try {
      $stmt->execute();
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    } catch(\PDOException $e) {
      $this->setErrorInfo($e->getMessage());
      $this->setErrorCode($e->getCode());
      return false;
    }
  }

  public function getDataById($id) {
    $sql = 'select *
            from `anggota`
            where `id_anggota` = :id_anggota
          ';
            
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_anggota', $id);
    try {
      $stmt->execute();
      return $stmt->fetch(\PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
      $this->setErrorInfo($e->getMessage());
      $this->setErrorCode($e->getCode());
      return false;
    }
  }

  public function add($data) {
    $sql = 'insert into `anggota` (`nama`, `keterangan`)
            values(:nama, :keterangan)';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':nama', $data['nama']);
    $stmt->bindParam(':keterangan', $data['keterangan']);

    try {
      $stmt->execute();
      $this->setLastInsertId($this->db->lastInsertId());
      return true;
    } catch(\PDOException $e) {
      $this->setErrorInfo($e->getMessage());
      $this->setErrorCode($e->getCode());
      return false;
    }
  }

  public function edit($id, $data) {
    $sql = 'update `anggota` set `nama` = :nama, `keterangan` = :keterangan
            where `id_anggota` = :id_anggota';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':nama', $data['nama']);
    $stmt->bindParam(':keterangan', $data['keterangan']);
    $stmt->bindParam(':id_anggota', $id);
    try {
      $stmt->execute();
      return true;
    } catch(\PDOException $e) {
      $this->setErrorInfo($e->getMessage());
      $this->setErrorCode($e->getCode());
      return false;
    }
  }

  public function delete($id) {
    $sql = 'delete from `anggota` where `id_anggota` = :id_anggota';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_anggota', $id);
    try {
      $stmt->execute();
      return true;
    } catch(\PDOException $e) {
      $this->setErrorInfo($e->getMessage());
      $this->setErrorCode($e->getCode());
      return false;
    }
  }

}

?>