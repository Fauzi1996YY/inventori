<?php

namespace App\Model;

class SubKategoriJurnal extends \App\Core\Model {

  public function getPaginatedData($limit, $id_kategori_jurnal = 0) {
    
    $sql = 'select `sub_kategori_jurnal`.*,
              `kategori_jurnal`.`nama` as `nama_kategori_jurnal`, 
              count(`jurnal_umum`.`id_sub_kategori_jurnal`) as `total_jurnal_umum`
            from `sub_kategori_jurnal` `sub_kategori_jurnal`
              left join `kategori_jurnal` `kategori_jurnal` on `sub_kategori_jurnal`.`id_kategori_jurnal` = `kategori_jurnal`.`id_kategori_jurnal`
              left join `jurnal_umum` `jurnal_umum` on `sub_kategori_jurnal`.`id_sub_kategori_jurnal` = `jurnal_umum`.`id_sub_kategori_jurnal`
            where `sub_kategori_jurnal`.`id_kategori_jurnal` = ' . (int)$id_kategori_jurnal . '
            group by `sub_kategori_jurnal`.`id_sub_kategori_jurnal`
            order by `sub_kategori_jurnal`.`id_sub_kategori_jurnal`
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
    $sql = 'select `sub_kategori_jurnal`.*,
              `kategori_jurnal`.`nama` as `nama_kategori_jurnal`,
              count(`jurnal_umum`.`id_sub_kategori_jurnal`) as `total_jurnal_umum`
            from `sub_kategori_jurnal` `sub_kategori_jurnal`
              left join `kategori_jurnal` `kategori_jurnal` on `sub_kategori_jurnal`.`id_kategori_jurnal` = `kategori_jurnal`.`id_kategori_jurnal`
              left join `jurnal_umum` `jurnal_umum` on `sub_kategori_jurnal`.`id_sub_kategori_jurnal` = `jurnal_umum`.`id_sub_kategori_jurnal`
            where `sub_kategori_jurnal`.`id_sub_kategori_jurnal` = :id_sub_kategori_jurnal
            group by `sub_kategori_jurnal`.`id_sub_kategori_jurnal`
            order by `sub_kategori_jurnal`.`id_sub_kategori_jurnal`
          ';
            
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_sub_kategori_jurnal', $id);
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
    $sql = 'insert into `sub_kategori_jurnal` (`id_kategori_jurnal`, `kode`, `nama`, `arus_kas`, `keterangan`)
            values(:id_kategori_jurnal, :kode, :nama, :arus_kas, :keterangan)';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_kategori_jurnal', $data['id_kategori_jurnal']);
    $stmt->bindParam(':kode', $data['kode']);
    $stmt->bindParam(':nama', $data['nama']);
    $stmt->bindParam(':arus_kas', $data['arus_kas']);
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
    $sql = 'update `sub_kategori_jurnal` set `kode` = :kode, `nama` = :nama, `arus_kas` = :arus_kas, `keterangan` = :keterangan
            where `id_sub_kategori_jurnal` = :id_sub_kategori_jurnal';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':kode', $data['kode']);
    $stmt->bindParam(':nama', $data['nama']);
    $stmt->bindParam(':arus_kas', $data['arus_kas']);
    $stmt->bindParam(':keterangan', $data['keterangan']);
    $stmt->bindParam(':id_sub_kategori_jurnal', $id);
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
    $sql = 'delete from `sub_kategori_jurnal` where `id_sub_kategori_jurnal` = :id_sub_kategori_jurnal';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_sub_kategori_jurnal', $id);
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