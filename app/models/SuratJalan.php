<?php

namespace App\Model;

class SuratJalan extends \App\Core\Model {

  public function getTodaysAndActiveData() {
    
    $sql = 'select a.*, b.`nama` as `nama_jalur_pengiriman`, c.`nama` as `nama_sopir_1`, d.`nama` as `nama_sopir_2`,
              (select count(`id_muatan`) from `muatan` where `id_surat_jalan` = a.`id_surat_jalan`) as `total_muatan`,
              (select count(`id_muatan`) from `muatan` where `id_surat_jalan` = a.`id_surat_jalan` and `validasi_muatan` = 2) as `total_muatan_tervalidasi`,
              (select count(`id_muatan`) from `muatan` where `id_surat_jalan` = a.`id_surat_jalan` and `muatan_selesai` = 1) as `total_muatan_selesai`
            from `surat_jalan` a
              left join `jalur_pengiriman` b on a.`id_jalur_pengiriman` = b.`id_jalur_pengiriman`
              left join `user` c on a.`id_user_1` = c.`id_user`
              left join `user` d on a.`id_user_2` = d.`id_user`
            where a.`tanggal` = curdate() or a.`validasi_setoran` = 0
            group by a.`id_surat_jalan`
            order by b.`nama` asc
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

  public function getTodaysForSopir() {
    $sql = 'select a.*, b.`nama` as `nama_jalur_pengiriman`, c.`nama` as `nama_sopir_1`, d.`nama` as `nama_sopir_2`,
              (select count(`id_muatan`) from `muatan` where `id_surat_jalan` = a.`id_surat_jalan`) as `total_muatan`,
              (select count(`id_muatan`) from `muatan` where `id_surat_jalan` = a.`id_surat_jalan` and `validasi_muatan` = 2) as `total_muatan_tervalidasi`
            from `surat_jalan` a
              left join `jalur_pengiriman` b on a.`id_jalur_pengiriman` = b.`id_jalur_pengiriman`
              left join `user` c on a.`id_user_1` = c.`id_user`
              left join `user` d on a.`id_user_2` = d.`id_user`
            where a.`tanggal` = curdate()
              and a.`validasi_setoran` = 0
              and (`id_user_1` = ' . $_SESSION['id_user'] . ' or `id_user_2` = ' . $_SESSION['id_user'] . ')
            order by a.`id_surat_jalan` desc
            limit 1
            ';
    
    $this->setSql($sql);
    $stmt = $this->db->prepare($sql);
    
    try {
      $stmt->execute();
      return $stmt->fetch(\PDO::FETCH_ASSOC);
    } catch(\PDOException $e) {
      $this->setErrorInfo($e->getMessage());
      $this->setErrorCode($e->getCode());
      return false;
    }
  }

  public function getDataById($id) {
    $sql = 'select a.*, count(b.`id_muatan`) as `muatan_aktif`, c.`nama` as `nama_jalur_pengiriman`, d.`nama` as `nama_sopir_1`, e.`nama` as `nama_sopir_2`,
              (select count(`id_muatan`) from `muatan` where `id_surat_jalan` = a.`id_surat_jalan`) as `total_muatan`,
              (select count(`id_muatan`) from `muatan` where `id_surat_jalan` = a.`id_surat_jalan` and `validasi_muatan` = 2) as `total_muatan_tervalidasi`,
              (select count(`id_muatan`) from `muatan` where `id_surat_jalan` = a.`id_surat_jalan` and `muatan_selesai` = 1) as `total_muatan_selesai`
            from `surat_jalan` a
              left join `muatan` b on a.`id_surat_jalan` = b.`id_surat_jalan` and b.`validasi_muatan` = 1
              left join `jalur_pengiriman` c on a.`id_jalur_pengiriman` = c.`id_jalur_pengiriman`
              left join `user` d on a.`id_user_1` = d.`id_user`
              left join `user` e on a.`id_user_2` = e.`id_user`
            where a.`id_surat_jalan` = :id_surat_jalan
            group by a.`id_surat_jalan`
            ';
            
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_surat_jalan', $id);

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

    $sql = 'insert into `surat_jalan`
            (`id_jalur_pengiriman`, `id_user_1`, `id_user_2`, `tanggal`, `waktu_mulai`)
            values(:id_jalur_pengiriman, :id_user_1, :id_user_2, curdate(), curtime())';
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_jalur_pengiriman', $data['id_jalur_pengiriman']);
    $stmt->bindParam(':id_user_1', $data['id_user_1']);
    $stmt->bindParam(':id_user_2', $data['id_user_2']);
    
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

    $sql = 'update `surat_jalan` set
            `id_jalur_pengiriman` = :id_jalur_pengiriman,
            `id_user_1` = :id_user_1,
            `id_user_2` = :id_user_2
            where `id_surat_jalan` = :id_surat_jalan and `validasi_setoran` != 1';

    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_jalur_pengiriman', $data['id_jalur_pengiriman']);
    $stmt->bindParam(':id_user_1', $data['id_user_1']);
    $stmt->bindParam(':id_user_2', $data['id_user_2']);
    $stmt->bindParam(':id_surat_jalan', $id);
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
    $sql = 'delete from `surat_jalan`
            where `id_surat_jalan` = :id_surat_jalan
              and `id_surat_jalan` not in(select `id_surat_jalan` from `muatan` where `id_surat_jalan` = :id_surat_jalan and `validasi_muatan` = 1)
            ';
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_surat_jalan', $id);
    try {
      $stmt->execute();
      return true;
    } catch(\PDOException $e) {
      $this->setErrorInfo($e->getMessage());
      $this->setErrorCode($e->getCode());
      return false;
    }
  }

  public function setor($id, $jumlah) {

    $sql = 'update `surat_jalan` set
            `jumlah_cash` = :jumlah_cash,
            `waktu_selesai` = curtime(),
            `validasi_setoran` = 1
            where `id_surat_jalan` = :id_surat_jalan and `validasi_setoran` != 1';

    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':jumlah_cash', $jumlah);
    $stmt->bindParam(':id_surat_jalan', $id);
    
    try {
      $stmt->execute();
      return true;
    } catch(\PDOException $e) {
      $this->setErrorInfo($e->getMessage());
      $this->setErrorCode($e->getCode());
      return false;
    }
  }

  public function getSetoranPerDate($date) {
    $sql = 'select `surat_jalan`.`id_surat_jalan`, `surat_jalan`.`jumlah_cash`,
              `jalur_pengiriman`.`nama` as `nama_jalur_pengiriman`,
              sum(`biaya_operasional`.`jumlah`) as `jumlah_biaya_operasional`,
              sum(`kasbon`.`jumlah`) as `jumlah_kasbon`
            from `surat_jalan` `surat_jalan`
              left join `jalur_pengiriman` `jalur_pengiriman` on `surat_jalan`.`id_jalur_pengiriman` = `jalur_pengiriman`.`id_jalur_pengiriman`
              left join (select `id_surat_jalan`, sum(`jumlah`) as `jumlah` from `biaya_operasional` group by `id_surat_jalan`) `biaya_operasional` on `surat_jalan`.`id_surat_jalan` = `biaya_operasional`.`id_surat_jalan`
              left join (select `id_surat_jalan`, sum(`jumlah`) as `jumlah` from `kasbon` group by `id_surat_jalan`) `kasbon` on `surat_jalan`.`id_surat_jalan` = `kasbon`.`id_surat_jalan`
            where `surat_jalan`.`tanggal` = :tanggal and `surat_jalan`.`validasi_setoran` = 1
            group by `surat_jalan`.`id_surat_jalan`';
    
    $this->setSql($sql);
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':tanggal', $date);
    
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