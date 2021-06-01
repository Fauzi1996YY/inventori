<?php

namespace App\Model;

class Kasbon extends \App\Core\Model {

  public function getAllData($id_surat_jalan) {
    
    $sql = 'select a.*, b.`nama`
            from `kasbon` a
              left join `user` b on a.`id_user` = b.`id_user`
            where a.`id_surat_jalan` = :id_surat_jalan
            order by a.`id_kasbon` asc
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
    
    $sql = 'select a.*, b.`nama`
            from `kasbon` a
              left join `user` b on a.`id_user` = b.`id_user`
            where `id_kasbon` = :id_kasbon
            ';
            
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_kasbon', $id);
    
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
    $sql = 'insert into `kasbon`
              (`id_surat_jalan`, `id_user`, `jumlah`)
              values(:id_surat_jalan, :id_user, :jumlah)';
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_surat_jalan', $data['id_surat_jalan']);
    $stmt->bindParam(':id_user', $data['id_user']);
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
    
    $sql = 'update `kasbon`
            set `id_user` = :id_user,
              `jumlah` = :jumlah
            where `id_kasbon` = :id_kasbon';
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_user', $data['id_user']);
    $stmt->bindParam(':jumlah', $data['jumlah']);
    $stmt->bindParam(':id_kasbon', $id);

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
    
    $sql = 'delete from `kasbon`
            where `id_kasbon` = :id_kasbon';
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_kasbon', $id);

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