<?php

namespace App\Model;

class Operasional extends \App\Core\Model {

  public function getAllData($id_surat_jalan) {
    
    $sql = 'select *
            from `biaya_operasional`
            where `id_surat_jalan` = :id_surat_jalan
            order by `id_biaya_operasional` asc
            ';
            
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_surat_jalan', $id_surat_jalan);

    try {
      $stmt->execute();
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
      $this->setErrorInfo($e->getMessage());
      $this->setErrorCode($e->getCode());
      return false;
    }
  }

  public function getDataById($id) {
    
    $sql = 'select *
            from `biaya_operasional`
            where `id_biaya_operasional` = :id_biaya_operasional
            ';
            
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_biaya_operasional', $id);
    
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
    $sql = 'insert into `biaya_operasional`
              (`id_surat_jalan`, `keterangan`, `jumlah`)
              values(:id_surat_jalan, :keterangan, :jumlah)';
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_surat_jalan', $data['id_surat_jalan']);
    $stmt->bindParam(':keterangan', $data['keterangan']);
    $stmt->bindParam(':jumlah', $data['jumlah']);

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
    
    $sql = 'update `biaya_operasional`
            set `keterangan` = :keterangan,
              `jumlah` = :jumlah
            where `id_biaya_operasional` = :id_biaya_operasional';
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':keterangan', $data['keterangan']);
    $stmt->bindParam(':jumlah', $data['jumlah']);
    $stmt->bindParam(':id_biaya_operasional', $id);

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
    
    $sql = 'delete from `biaya_operasional`
            where `id_biaya_operasional` = :id_biaya_operasional';
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_biaya_operasional', $id);

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