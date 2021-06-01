<?php

namespace App\Model;

class Muatan extends \App\Core\Model {

  public function getDataByIdSuratJalan($id_surat_jalan) {
    
    $sql = 'select * from `muatan` where `id_surat_jalan` = :id_surat_jalan';
    
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

  // public function totalBongkar($id_surat_jalan) {
  //   $sql = 'select count(*) as `total`, sum(if(`validasi_muatan` = 2, 1, 0)) as `bongkar`
  //           from `muatan`
  //           where `id_surat_jalan` = :id_surat_jalan
  //           ';
            
  //   $stmt = $this->db->prepare($sql);
  //   $stmt->bindParam(':id_surat_jalan', $id_surat_jalan);

  //   try {
  //     $stmt->execute();
  //     return $stmt->fetch(\PDO::FETCH_ASSOC);
  //   } catch(PDOException $e) {
  //     $this->setErrorInfo($e->getMessage());
  //     $this->setErrorCode($e->getCode());
  //     return false;
  //   }
  // }

  public function validateByIdSuratJalan($id_surat_jalan) {
    $sql = 'update `muatan`
            set `validasi_muatan` = 1
            where `id_surat_jalan` = :id_surat_jalan
            order by `id_muatan` desc
            limit 1
            ';
            
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_surat_jalan', $id_surat_jalan);

    try {
      $stmt->execute();
      return true;
    } catch(PDOException $e) {
      $this->setErrorInfo($e->getMessage());
      $this->setErrorCode($e->getCode());
      return false;
    }
  }

  public function getDataById($id) {
    $sql = 'select a.*, b.`tanggal`
            from `muatan` a
              left join `surat_jalan` b on a.`id_surat_jalan` = b.`id_surat_jalan`
            where a.`id_muatan` = :id_muatan
            ';
            
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_muatan', $id);

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

    $sql = 'insert into `muatan`
            (`id_surat_jalan`, `muatan_tabung_besar`, `muatan_tabung_kecil`, `muatan_serut`, `waktu`)
            values(:id_surat_jalan, :muatan_tabung_besar, :muatan_tabung_kecil, :muatan_serut, curtime())';
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_surat_jalan', $data['id_surat_jalan']);
    $stmt->bindParam(':muatan_tabung_besar', $data['muatan_tabung_besar']);
    $stmt->bindParam(':muatan_tabung_kecil', $data['muatan_tabung_kecil']);
    $stmt->bindParam(':muatan_serut', $data['muatan_serut']);
    
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

  public function edit($id_muatan, $data) {
    $sql = 'update `muatan`
            set `muatan_tabung_besar` = :muatan_tabung_besar,
              `muatan_tabung_kecil` = :muatan_tabung_kecil,
              `muatan_serut` = :muatan_serut
            where `id_muatan` = :id_muatan
            ';
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_muatan', $id_muatan);
    $stmt->bindParam(':muatan_tabung_besar', $data['muatan_tabung_besar']);
    $stmt->bindParam(':muatan_tabung_kecil', $data['muatan_tabung_kecil']);
    $stmt->bindParam(':muatan_serut', $data['muatan_serut']);

    try {
      $stmt->execute();
      return true;
    } catch(PDOException $e) {
      $this->setErrorInfo($e->getMessage());
      $this->setErrorCode($e->getCode());
      return false;
    }
  }

  public function bongkar($id_muatan, $data) {
    $sql = 'update `muatan`
            set `kembali_tabung_besar` = :kembali_tabung_besar,
                `kembali_tabung_kecil` = :kembali_tabung_kecil,
                `kembali_serut` = :kembali_serut,
                `rusak_tabung_besar` = :rusak_tabung_besar,
                `rusak_tabung_kecil` = :rusak_tabung_kecil,
                `rusak_serut` = :rusak_serut,
                `muatan_selesai` = 1
            where `id_muatan` = :id_muatan
            ';
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_muatan', $id_muatan);
    $stmt->bindParam(':kembali_tabung_besar', $data['kembali_tabung_besar']);
    $stmt->bindParam(':kembali_tabung_kecil', $data['kembali_tabung_kecil']);
    $stmt->bindParam(':kembali_serut', $data['kembali_serut']);
    $stmt->bindParam(':rusak_tabung_besar', $data['rusak_tabung_besar']);
    $stmt->bindParam(':rusak_tabung_kecil', $data['rusak_tabung_kecil']);
    $stmt->bindParam(':rusak_serut', $data['rusak_serut']);

    try {
      $stmt->execute();
      return true;
    } catch(PDOException $e) {
      $this->setErrorInfo($e->getMessage());
      $this->setErrorCode($e->getCode());
      return false;
    }
  }

  public function delete($id) {
    $sql = 'delete
            from `muatan`
            where `id_muatan` = :id_muatan and `validasi_muatan` = 0';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_muatan', $id);
    try {
      $stmt->execute();
      return true;
    } catch(\PDOException $e) {
      $this->setErrorInfo($e->getMessage());
      $this->setErrorCode($e->getCode());
      return false;
    }
  }

  public function bongkarMuatanByIdSuratJalan($id_surat_jalan) {
    $sql = 'update `muatan`
            set `validasi_muatan` = 2
            where `id_surat_jalan` = :id_surat_jalan
            order by `id_muatan` desc
            limit 1
            ';
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_surat_jalan', $id_surat_jalan);
    
    try {
      $stmt->execute();
      return true;
    } catch(PDOException $e) {
      $this->setErrorInfo($e->getMessage());
      $this->setErrorCode($e->getCode());
      return false;
    }
  }

}

?>