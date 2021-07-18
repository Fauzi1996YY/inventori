<?php

namespace App\Model;

class JurnalUmum extends \App\Core\Model {

  public function getDataFiltered($id_rekening, $monthandyear) {
    
    $monthandyear = explode('-', $monthandyear);
    $min = $monthandyear[0] . '-' . $monthandyear[1] . '-01';
    $max = $monthandyear[0] . '-' . $monthandyear[1] . '-31';

    $sql = 'select `jurnal_umum`.*,
              `sub_kategori_jurnal`.`nama`, `sub_kategori_jurnal`.`arus_kas`,
              `rekening`.`jenis_rekening`
            from `jurnal_umum` `jurnal_umum`
              left join `sub_kategori_jurnal` `sub_kategori_jurnal` on `jurnal_umum`.`id_sub_kategori_jurnal` = `sub_kategori_jurnal`.`id_sub_kategori_jurnal`
              left join `rekening` `rekening` on `jurnal_umum`.`id_rekening` = `rekening`.`id_rekening`
            where `tanggal` >= :tanggal_1 and `tanggal` <= :tanggal_2 and `jurnal_umum`.`id_rekening` = :id_rekening';

    $this->setSql($sql);
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':tanggal_1', $min);
    $stmt->bindParam(':tanggal_2', $max);
    $stmt->bindParam(':id_rekening', $id_rekening);
    
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
    
    $sql = 'select coalesce(min(year(`tanggal`)), year(curdate())) as `min`, coalesce(max(year(`tanggal`)), year(curdate())) as `max`
            from `jurnal_umum`
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
    $sql = 'select `jurnal_umum`.*,
              `rekening`.`id_rekening`, `rekening`.`jenis_rekening`,
              `sub_kategori_jurnal`.`id_sub_kategori_jurnal`, `sub_kategori_jurnal`.`nama`
            from `jurnal_umum` `jurnal_umum`
              left join `rekening` `rekening` on `jurnal_umum`.`id_rekening` = `rekening`.`id_rekening`
              left join `sub_kategori_jurnal` `sub_kategori_jurnal` on `jurnal_umum`.`id_sub_kategori_jurnal` = `sub_kategori_jurnal`.`id_sub_kategori_jurnal`
            where `jurnal_umum`.`id_jurnal_umum` = :id_jurnal_umum
          ';
            
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_jurnal_umum', $id);
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
    $sql = 'insert into `jurnal_umum` (`id_rekening`, `id_sub_kategori_jurnal`, `jumlah`, `tanggal`)
            values(:id_rekening, :id_sub_kategori_jurnal, :jumlah, :tanggal)';
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_rekening', $data['id_rekening']);
    $stmt->bindParam(':id_sub_kategori_jurnal', $data['id_sub_kategori_jurnal']);
    $stmt->bindParam(':jumlah', $data['jumlah']);
    $stmt->bindParam(':tanggal', $data['tanggal']);

    try {
      $stmt->execute();
      $this->setLastInsertId($this->db->lastInsertId());
      return true;
    } catch(\PDOException $e) {
      $this->setErrorInfo($e->getMessage());
      $this->setErrorCode($e->getCode());
      echo $e->getMessage();
      return false;
    }
  }

  public function edit($id, $data) {
    $sql = 'update `jurnal_umum` set `id_sub_kategori_jurnal` = :id_sub_kategori_jurnal, `jumlah` = :jumlah, `tanggal` = :tanggal
            where `id_jurnal_umum` = :id_jurnal_umum';
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_sub_kategori_jurnal', $data['id_sub_kategori_jurnal']);
    $stmt->bindParam(':jumlah', $data['jumlah']);
    $stmt->bindParam(':tanggal', $data['tanggal']);
    $stmt->bindParam(':id_jurnal_umum', $id);

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
    
    $sql = 'delete from `jurnal_umum` where `id_jurnal_umum` = :id_jurnal_umum';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_jurnal_umum', $id);

    try {
      $stmt->execute();
      return true;
    } catch(\PDOException $e) {
      $this->setErrorInfo($e->getMessage());
      $this->setErrorCode($e->getCode());
      return false;
    }
  }

  public function calculateSaldo($id_rekening, $date) {
    $data = $this->getDataFiltered($id_rekening, $date);
    $saldo = 0;
    foreach ($data as $k => $v) {
      if ($v['arus_kas'] == 'masuk') {
        $saldo+= $v['jumlah'];
      }
      else {
        $saldo-= $v['jumlah'];
      }
    }

    $waktu = explode('-', $date);
    $tahun = $waktu[0];
    $bulan = $waktu[1];
    $waktu = $tahun . '-' . $bulan . '-01';

    /* Insert if data doesn't exist */
    $sql = 'insert into `saldo_jurnal` (`id_rekening`, `waktu`, `saldo`)
            values(:id_rekening, :waktu, :saldo)';

    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':saldo', $saldo);
    $stmt->bindParam(':id_rekening', $id_rekening);
    $stmt->bindParam(':waktu', $waktu);

    try {
      $stmt->execute();
      return true;
    } catch(\PDOException $e) {}

    /* Update otherwise */
    $sql = 'update `saldo_jurnal` set `saldo` = :saldo
            where `id_rekening` = :id_rekening and `waktu` = :waktu';
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':saldo', $saldo);
    $stmt->bindParam(':id_rekening', $id_rekening);
    $stmt->bindParam(':waktu', $waktu);

    try {
      $stmt->execute();
      return true;
    } catch(\PDOException $e) {}

  }

  public function getPreviousSaldo($id_rekening, $waktu) {
    $sql = 'select coalesce(sum(`saldo`), 0) as `jumlah`
            from `saldo_jurnal`
            where `id_rekening` = :id_rekening and `waktu` < :waktu
          ';
            
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_rekening', $id_rekening);
    $stmt->bindParam(':waktu', $waktu);

    try {
      $stmt->execute();
      return $stmt->fetch(\PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
      $this->setErrorInfo($e->getMessage());
      $this->setErrorCode($e->getCode());
      return false;
    }
  }

}

?>