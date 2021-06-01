<?php

namespace App\Model;

class Sopir extends \App\Core\Model {

  public function getAllData() {
    
    $sql = 'select *
            from `user`
            where `role` = \'sopir\'
            order by `nama`
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
    
    $sql = 'select * from `user`
            where `id_user` not in (select `id_user_1` from `surat_jalan` where `tanggal` = curdate() and `id_surat_jalan` != :id_surat_jalan)
              and `id_user` not in (select `id_user_2` from `surat_jalan` where `tanggal` = curdate() and `id_surat_jalan` != :id_surat_jalan)
                and `role` = \'sopir\'
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

}

?>