<?php

namespace App\Model;

class Rekening extends \App\Core\Model {

  public function getAllData() {
    
    $sql = 'select * from `rekening` order by `jenis_rekening` asc';
    
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
    
    $sql = 'select `rekening`.*, count(`jurnal_umum`.`id_jurnal_umum`) as `total_jurnal_umum`
            from `rekening` `rekening`
              left join `jurnal_umum` `jurnal_umum` on `rekening`.`id_rekening` = `jurnal_umum`.`id_rekening`
            group by `rekening`.`id_rekening`
            order by `rekening`.`id_rekening`
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
    $sql = 'select `rekening`.*, count(`jurnal_umum`.`id_jurnal_umum`) as `total_jurnal_umum`
            from `rekening` `rekening`
              left join `jurnal_umum` `jurnal_umum` on `rekening`.`id_rekening` = `jurnal_umum`.`id_rekening`
            where `rekening`.`id_rekening` = :id_rekening
            group by `rekening`.`id_rekening`
            order by `rekening`.`id_rekening`
          ';
            
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_rekening', $id);
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
    $sql = 'insert into `rekening` (`bank`, `kantor_cabang`, `nomor_rekening`, `nama_pemilik_rekening`, `jenis_rekening`)
            values(:bank, :kantor_cabang, :nomor_rekening, :nama_pemilik_rekening, :jenis_rekening)';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':bank', $data['bank']);
    $stmt->bindParam(':kantor_cabang', $data['kantor_cabang']);
    $stmt->bindParam(':nomor_rekening', $data['nomor_rekening']);
    $stmt->bindParam(':nama_pemilik_rekening', $data['nama_pemilik_rekening']);
    $stmt->bindParam(':jenis_rekening', $data['jenis_rekening']);

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
    $sql = 'update `rekening` set `bank` = :bank, `kantor_cabang` = :kantor_cabang, `nomor_rekening` = :nomor_rekening, 
              `nama_pemilik_rekening` = :nama_pemilik_rekening, `jenis_rekening` = :jenis_rekening
            where `id_rekening` = :id_rekening';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':bank', $data['bank']);
    $stmt->bindParam(':kantor_cabang', $data['kantor_cabang']);
    $stmt->bindParam(':nomor_rekening', $data['nomor_rekening']);
    $stmt->bindParam(':nama_pemilik_rekening', $data['nama_pemilik_rekening']);
    $stmt->bindParam(':jenis_rekening', $data['jenis_rekening']);
    $stmt->bindParam(':id_rekening', $id);
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
    $sql = 'delete from `rekening` where `id_rekening` = :id_rekening';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_rekening', $id);
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