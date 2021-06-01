<?php

namespace App\Model;

class JalurPengiriman extends \App\Core\Model {

  public function getAllData() {
    $sql = 'select a.*, count(distinct b.`id_surat_jalan`) as `total_surat_jalan`
            from `jalur_pengiriman` a
            left join `surat_jalan` b on a.`id_jalur_pengiriman` = b.`id_jalur_pengiriman`
            group by a.`id_jalur_pengiriman`
            order by a.`id_jalur_pengiriman`
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

  public function getTodaysInactive($id_surat_jalan = 0) {
    $sql = 'select * from `jalur_pengiriman`
            where `id_jalur_pengiriman` not in (select `id_jalur_pengiriman` from `surat_jalan` where `tanggal` = curdate() && `id_surat_jalan` != :id_surat_jalan)
            order by `nama`
            ';
    
    $this->setSql($sql);
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_surat_jalan', $id_surat_jalan);
    
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
    $sql = 'select a.*, count(distinct b.`id_surat_jalan`) as `total_surat_jalan`
            from `jalur_pengiriman` a
            left join `surat_jalan` b on a.`id_jalur_pengiriman` = b.`id_jalur_pengiriman`
            where a.`id_jalur_pengiriman` = :id_jalur_pengiriman
            group by a.`id_jalur_pengiriman`';
            
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_jalur_pengiriman', $id);
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
    $sql = 'insert into `jalur_pengiriman` (`nama`) values(:nama)';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':nama', $data['nama']);
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
    $sql = 'update `jalur_pengiriman` set `nama` = :nama where `id_jalur_pengiriman` = :id_jalur_pengiriman';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':nama', $data['nama']);
    $stmt->bindParam(':id_jalur_pengiriman', $id);
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
    $sql = 'delete from `jalur_pengiriman` where `id_jalur_pengiriman` = :id_jalur_pengiriman';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_jalur_pengiriman', $id);
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