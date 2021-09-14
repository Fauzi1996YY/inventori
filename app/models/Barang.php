<?php

namespace App\Model;

class Barang extends \App\Core\Model {

  public function getAllData() {
    
    $sql = 'select `barang`.*,
              `kategori`.`kode` as `kode_kategori`, `kategori`.`nama` as `nama_kategori`
            from `barang` `barang`
              left join `kategori` `kategori` on `barang`.`id_kategori` = `kategori`.`id_kategori`
            order by `barang`.`id_barang`
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
    
    $sql = 'select `barang`.*,
              `kategori`.`kode` as `kode_kategori`, `kategori`.`nama` as `nama_kategori`
            from `barang` `barang`
              left join `kategori` `kategori` on `barang`.`id_kategori` = `kategori`.`id_kategori`
            order by `barang`.`id_barang`
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
    $sql = 'select `barang`.*,
              `kategori`.`kode` as `kode_kategori`, `kategori`.`nama` as `nama_kategori`
            from `barang` `barang`
              left join `kategori` `kategori` on `barang`.`id_kategori` = `kategori`.`id_kategori`
            where `barang`.`id_barang` = :id_barang
          ';
            
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_barang', $id);
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
    $sql = 'insert into `barang` (`id_kategori`, `kode`, `nama`, `brand`, `tahun_pembuatan`, `jumlah`)
            values(:id_kategori, :kode, :nama, :brand, :tahun_pembuatan, :jumlah)';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_kategori', $data['id_kategori']);
    $stmt->bindParam(':kode', $data['kode']);
    $stmt->bindParam(':nama', $data['nama']);
    $stmt->bindParam(':brand', $data['brand']);
    $stmt->bindParam(':tahun_pembuatan', $data['tahun_pembuatan']);
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
    
    $sql = 'update `barang`
            set `id_kategori` = :id_kategori,
                `kode` = :kode,
                `nama` = :nama,
                `brand` = :brand,
                `tahun_pembuatan` = :tahun_pembuatan,
                `jumlah` = :jumlah
            where `id_barang` = :id_barang';
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_kategori', $data['id_kategori']);
    $stmt->bindParam(':kode', $data['kode']);
    $stmt->bindParam(':nama', $data['nama']);
    $stmt->bindParam(':brand', $data['brand']);
    $stmt->bindParam(':tahun_pembuatan', $data['tahun_pembuatan']);
    $stmt->bindParam(':jumlah', $data['jumlah']);
    $stmt->bindParam(':id_barang', $id);
    
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
    $sql = 'delete from `barang` where `id_barang` = :id_barang';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_barang', $id);
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