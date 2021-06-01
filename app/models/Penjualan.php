<?php

namespace App\Model;

class Penjualan extends \App\Core\Model {

  public function getPenjualanByDate($tanggal) {
    
    $sql = 'select a.*,
              sum(b.`es_tabung_besar`) as `tabung_besar`, sum(b.`es_tabung_kecil`) as `tabung_kecil`, sum(b.`es_serut`) as `serut`,
              c.`nama`,
              (select sum(`total_harga`) from `penjualan` where `id_surat_jalan` = a.`id_surat_jalan` and `metode_pembayaran` = \'cash\') as `cash`,
              (select sum(`total_harga`) from `penjualan` where `id_surat_jalan` = a.`id_surat_jalan` and `metode_pembayaran` = \'invoice\') as `invoice`
            from `surat_jalan` a
              left join `penjualan` b on a.`id_surat_jalan` = b.`id_surat_jalan`
              left join `jalur_pengiriman` c on a.`id_jalur_pengiriman` = c.`id_jalur_pengiriman`
            where `tanggal` = :tanggal
            group by a.`id_surat_jalan`
            order by c.`nama` asc
            ';
    
    $this->setSql($sql);
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':tanggal', $tanggal);
    
    try {
      $stmt->execute();
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    } catch(\PDOException $e) {
      $this->setErrorInfo($e->getMessage());
      $this->setErrorCode($e->getCode());
      return false;
    }
  }

  public function getMinMaxYear() {
    
    $sql = 'select min(year(`tanggal`)) as `min`, max(year(`tanggal`)) as `max`
            from `surat_jalan`
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

  public function add($data) {
    $sql = 'insert into `penjualan`
            (`id_user`, `id_surat_jalan`, `es_tabung_besar`, `es_tabung_kecil`, `es_serut`, `bonus_es_tabung_kecil`, `total_harga`, `metode_pembayaran`)
            values (:id_user, :id_surat_jalan, :es_tabung_besar, :es_tabung_kecil, :es_serut, :bonus_es_tabung_kecil, :total_harga, :metode_pembayaran)
            ';
            
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_user', $data['id_user']);
    $stmt->bindParam(':id_surat_jalan', $data['id_surat_jalan']);
    $stmt->bindParam(':es_tabung_besar', $data['es_tabung_besar']);
    $stmt->bindParam(':es_tabung_kecil', $data['es_tabung_kecil']);
    $stmt->bindParam(':es_serut', $data['es_serut']);
    $stmt->bindParam(':bonus_es_tabung_kecil', $data['bonus_es_tabung_kecil']);
    $stmt->bindParam(':total_harga', $data['total_harga']);
    $stmt->bindParam(':metode_pembayaran', $data['metode_pembayaran']);
    
    try {
      $stmt->execute();
      $this->setLastInsertId($this->db->lastInsertId());
      return true;
    } catch(PDOException $e) {
      $this->setErrorInfo($e->getMessage());
      $this->setErrorCode($e->getCode());
      return false;
    }
  }

  public function getTodaysPenjualanByIdUser($id_user) {
    $sql = 'select count(a.`id_penjualan`) as `total`
            from `penjualan` a
              left join `surat_jalan` b on a.`id_surat_jalan` = b.`id_surat_jalan` and b.`tanggal` = curdate()
            where a.`id_user` = :id_user
            ';
    
    $this->setSql($sql);
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_user', $id_user);
    
    try {
      $stmt->execute();
      return $stmt->fetch(\PDO::FETCH_ASSOC);
    } catch(\PDOException $e) {
      $this->setErrorInfo($e->getMessage());
      $this->setErrorCode($e->getCode());
      return false;
    }
  }

  public function getDataByIdSuratJalan($id_surat_jalan) {
    $sql = 'select a.*, b.`nama`
            from `penjualan` a
              left join `user` b on a.`id_user` = b.`id_user`
            where a.`id_surat_jalan` = :id_surat_jalan
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

  public function getDataPelangganByIdSuratJalan($id_surat_jalan) {
    $sql = 'select a.*, b.`id_user`, b.`nama`
            from `penjualan` a
              left join `user` b on a.`id_user` = b.`id_user`
            where `id_surat_jalan` = :id_surat_jalan
            order by a.`id_penjualan`
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