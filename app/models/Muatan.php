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

  public function pindahan($id_surat_jalan) {

    $sql = 'select a.*,
              b.`id_user_1`, b.`id_user_2`,
                c.`id_user_1`, c.`id_user_2`,
                d.`nama` as `sj_1_nama_1`,
                e.`nama` as `sj_1_nama_2`,
                f.`nama` as `sj_2_nama_1`,
                g.`nama` as `sj_2_nama_2`,
                h.`nama` as `sj_1_nama_jalur_pengiriman`,
                i.`nama` as `sj_2_nama_jalur_pengiriman`
            from `pindah_muatan` a
              left join `surat_jalan` b on a.`id_surat_jalan_1` = b.`id_surat_jalan`
                left join `surat_jalan` c on a.`id_surat_jalan_2` = c.`id_surat_jalan`
                left join `user` d on b.`id_user_1` = d.`id_user`
                left join `user` e on b.`id_user_2` = e.`id_user`
                left join `user` f on c.`id_user_1` = f.`id_user`
                left join `user` g on c.`id_user_2` = g.`id_user`
                left join `jalur_pengiriman` h on b.`id_jalur_pengiriman` = h.`id_jalur_pengiriman`
                left join `jalur_pengiriman` i on c.`id_jalur_pengiriman` = i.`id_jalur_pengiriman`
            where 
            (`id_surat_jalan_1` = :id_surat_jalan or `id_surat_jalan_2` = :id_surat_jalan) and `validasi` = 0
            limit 1
            ';
    
    $this->setSql($sql);
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_surat_jalan', $id_surat_jalan);
    
    try {
      $stmt->execute();
      return $stmt->fetch(\PDO::FETCH_ASSOC);
    } catch(\PDOException $e) {
      $this->setErrorInfo($e->getMessage());
      $this->setErrorCode($e->getCode());
      return false;
    }
  }

  public function pindah($data) {
    $sql = 'insert into `pindah_muatan`
            (`id_surat_jalan_1`, `id_surat_jalan_2`, `tabung_besar`, `tabung_kecil`, `serut`)
            values(:id_surat_jalan_1, :id_surat_jalan_2, :tabung_besar, :tabung_kecil, :serut)';
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_surat_jalan_1', $data['id_surat_jalan_1']);
    $stmt->bindParam(':id_surat_jalan_2', $data['id_surat_jalan_2']);
    $stmt->bindParam(':tabung_besar', $data['tabung_besar']);
    $stmt->bindParam(':tabung_kecil', $data['tabung_kecil']);
    $stmt->bindParam(':serut', $data['serut']);
    
    try {
      $stmt->execute();
      return true;
    } catch(\PDOException $e) {
      $this->setErrorInfo($e->getMessage());
      $this->setErrorCode($e->getCode());
      return false;
    }
  }

  public function execPindahan($id_surat_jalan) {
    $pindahan = $this->pindahan($id_surat_jalan);

    if (!$pindahan) {
      return false;
    }

    $tabung_besar = (int) $pindahan['tabung_besar'];
    $tabung_kecil = (int) $pindahan['tabung_kecil'];
    $serut = (int) $pindahan['serut'];

    /* Increase penerima */
    $sql = 'update `muatan`
            set `terima_tabung_besar` = `terima_tabung_besar` + :tabung_besar,
              `terima_tabung_kecil` = `terima_tabung_kecil` + :tabung_kecil,
              `terima_serut` = `terima_serut` + :serut
            where `id_surat_jalan` = :id_surat_jalan
            order by `id_muatan` desc
            limit 1
            ';
            
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':tabung_besar', $tabung_besar);
    $stmt->bindParam(':tabung_kecil', $tabung_kecil);
    $stmt->bindParam(':serut', $serut);
    $stmt->bindParam(':id_surat_jalan', $id_surat_jalan);
    $stmt->execute();

    /* Decrease pengirim */
    $sql = 'update `muatan`
            set `pindah_tabung_besar` = `pindah_tabung_besar` + :tabung_besar,
              `pindah_tabung_kecil` = `pindah_tabung_kecil` + :tabung_kecil,
              `pindah_serut` = `pindah_serut` + :serut
            where `id_surat_jalan` = :id_surat_jalan
            order by `id_muatan` desc
            limit 1
            ';
            
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':tabung_besar', $tabung_besar);
    $stmt->bindParam(':tabung_kecil', $tabung_kecil);
    $stmt->bindParam(':serut', $serut);
    $stmt->bindParam(':id_surat_jalan', $pindahan['id_surat_jalan_1']);
    $stmt->execute();

    $this->deletePindahan($id_surat_jalan);

  }

  public function deletePindahan($id_surat_jalan) {
    $sql = 'delete
            from `pindah_muatan`
            where `id_surat_jalan_1` = :id_surat_jalan or  `id_surat_jalan_2` = :id_surat_jalan';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_surat_jalan', $id_surat_jalan);
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