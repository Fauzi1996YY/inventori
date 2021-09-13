<?php

namespace App\Model;

class Kategori extends \App\Core\Model {

  public function getAllData() {
    
    $sql = 'select `kategori`.*, count(`barang`.`id_barang`) as `total_barang`
            from `kategori` `kategori`
              left join `barang` `barang` on `kategori`.`id_kategori` = `barang`.`id_kategori`
            group by `kategori`.`id_kategori`
            order by `kategori`.`id_kategori`
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
    
    $sql = 'select `kategori`.*, count(`barang`.`id_barang`) as `total_barang`
            from `kategori` `kategori`
              left join `barang` `barang` on `kategori`.`id_kategori` = `barang`.`id_kategori`
            group by `kategori`.`id_kategori`
            order by `kategori`.`id_kategori`
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
    $sql = 'select `kategori`.*, count(`barang`.`id_barang`) as `total_barang`
            from `kategori` `kategori`
              left join `barang` `barang` on `kategori`.`id_kategori` = `barang`.`id_kategori`
            where `kategori`.`id_kategori` = :id_kategori
            group by `kategori`.`id_kategori`
            order by `kategori`.`id_kategori`
          ';
            
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_kategori', $id);
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
    $sql = 'insert into `kategori` (`kode`, `nama`, `keterangan`)
            values(:kode, :nama, :keterangan)';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':kode', $data['kode']);
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
    $sql = 'update `kategori` set `kode` = :kode, `nama` = :nama, `keterangan` = :keterangan
            where `id_kategori` = :id_kategori';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':kode', $data['kode']);
    $stmt->bindParam(':nama', $data['nama']);
    $stmt->bindParam(':keterangan', $data['keterangan']);
    $stmt->bindParam(':id_kategori', $id);
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
    $sql = 'delete from `kategori` where `id_kategori` = :id_kategori';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_kategori', $id);
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