<?php

namespace App\Model;

class Peminjaman extends \App\Core\Model {

  public function getAllData() {
    
    $sql = 'select `peminjaman`.*,
              `barang`.`kode` as  `kode_barang`, `barang`.`nama` as `nama_barang`,
              `anggota`.`nama` as `nama_anggota`
            from `peminjaman` `peminjaman`
              left join `barang` `barang` on `peminjaman`.`id_barang` = `barang`.`id_barang`
              left join `anggota` `anggota` on `peminjaman`.`id_anggota` = `anggota`.`id_anggota`
            order by `peminjaman`.`id_peminjaman` desc
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
    
    $sql = 'select `peminjaman`.*,
              `barang`.`kode` as  `kode_barang`, `barang`.`nama` as `nama_barang`,
              `anggota`.`nama` as `nama_anggota`
            from `peminjaman` `peminjaman`
              left join `barang` `barang` on `peminjaman`.`id_barang` = `barang`.`id_barang`
              left join `anggota` `anggota` on `peminjaman`.`id_anggota` = `anggota`.`id_anggota`
            order by `peminjaman`.`id_peminjaman` desc
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
    $sql = 'select `peminjaman`.*,
              `barang`.`kode` as  `kode_barang`, `barang`.`nama` as `nama_barang`,
              `anggota`.`nama` as `nama_anggota`
            from `peminjaman` `peminjaman`
              left join `barang` `barang` on `peminjaman`.`id_barang` = `barang`.`id_barang`
              left join `anggota` `anggota` on `peminjaman`.`id_anggota` = `anggota`.`id_anggota`
            where `peminjaman`.`id_peminjaman` = :id_peminjaman
          ';
            
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_peminjaman', $id);
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
    $sql = 'insert into `peminjaman` (`id_barang`, `id_anggota`, `jumlah`, `tgl_peminjaman`, `tgl_pengembalian`)
            values(:id_barang, :id_anggota, :jumlah, :tgl_peminjaman, :tgl_pengembalian)';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_barang', $data['id_barang']);
    $stmt->bindParam(':id_anggota', $data['id_anggota']);
    $stmt->bindParam(':jumlah', $data['jumlah']);
    $stmt->bindParam(':tgl_peminjaman', $data['tgl_peminjaman']);
    $stmt->bindParam(':tgl_pengembalian', $data['tgl_pengembalian']);

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
    $sql = 'update `peminjaman` set `id_barang` = :id_barang, `id_anggota` = :id_anggota, `jumlah` = :jumlah, `tgl_peminjaman` = :tgl_peminjaman, `tgl_pengembalian` = :tgl_pengembalian
            where `id_peminjaman` = :id_peminjaman';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_barang', $data['id_barang']);
    $stmt->bindParam(':id_anggota', $data['id_anggota']);
    $stmt->bindParam(':jumlah', $data['jumlah']);
    $stmt->bindParam(':tgl_peminjaman', $data['tgl_peminjaman']);
    $stmt->bindParam(':tgl_pengembalian', $data['tgl_pengembalian']);
    $stmt->bindParam(':id_peminjaman', $id);
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
    $sql = 'delete from `peminjaman` where `id_peminjaman` = :id_peminjaman';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_peminjaman', $id);
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