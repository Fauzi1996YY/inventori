<?php

namespace App\Model;

class KategoriJurnal extends \App\Core\Model {

  public function getPaginatedData($limit) {
    
    $sql = 'select `kategori_jurnal`.*, count(`sub_kategori_jurnal`.`id_sub_kategori_jurnal`) as `total_sub_kategori_jurnal`
            from `kategori_jurnal` `kategori_jurnal`
              left join `sub_kategori_jurnal` `sub_kategori_jurnal` on `kategori_jurnal`.`id_kategori_jurnal` = `sub_kategori_jurnal`.`id_kategori_jurnal`
            group by `kategori_jurnal`.`id_kategori_jurnal`
            order by `kategori_jurnal`.`id_kategori_jurnal`
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
    $sql = 'select `kategori_jurnal`.*, count(`sub_kategori_jurnal`.`id_sub_kategori_jurnal`) as `total_sub_kategori_jurnal`
            from `kategori_jurnal` `kategori_jurnal`
              left join `sub_kategori_jurnal` `sub_kategori_jurnal` on `kategori_jurnal`.`id_kategori_jurnal` = `sub_kategori_jurnal`.`id_kategori_jurnal`
            where `kategori_jurnal`.`id_kategori_jurnal` = :id_kategori_jurnal
            group by `kategori_jurnal`.`id_kategori_jurnal`
            order by `kategori_jurnal`.`id_kategori_jurnal`
          ';
            
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_kategori_jurnal', $id);
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
    $sql = 'insert into `kategori_jurnal` (`kode`, `nama`, `keterangan`)
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
    $sql = 'update `kategori_jurnal` set `kode` = :kode, `nama` = :nama, `keterangan` = :keterangan
            where `id_kategori_jurnal` = :id_kategori_jurnal';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':kode', $data['kode']);
    $stmt->bindParam(':nama', $data['nama']);
    $stmt->bindParam(':keterangan', $data['keterangan']);
    $stmt->bindParam(':id_kategori_jurnal', $id);
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
    $sql = 'delete from `kategori_jurnal` where `id_kategori_jurnal` = :id_kategori_jurnal';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_kategori_jurnal', $id);
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