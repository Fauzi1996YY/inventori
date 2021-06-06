<?php

namespace App\Model;

class Pelanggan extends \App\Core\Model {

  public function getPaginatedData($limit, $filter, $orderKey, $orderValue) {
    
    $whereClause = array();
    $whereClauseText = '';
    $secondSelectorText = '';
    $orderKeyText = $orderKey;
    $orderValueText = $orderValue;

    /* Filter: fulltext `nama` */
    if ($filter['search'] != '') {
      $whereClause[] = ' match(a.`nama`, a.`no_telp`, a.`alamat`) against(\'' . $filter['search'] . '*\' in boolean mode) ';
      $secondSelectorText = ', match(a.`nama`, a.`no_telp`, a.`alamat`) against(\'' . $filter['search'] . '\') as `relevance` ';
      if (!isset($_GET['orderkey'])) {
        $orderKeyText = '`relevance`';
        $orderKeyValue = 'desc';
      }
    }

    if (count($whereClause) > 0) {
      $whereClauseText = ' and ' . implode(' and ', $whereClause);
    }
    
    $sql = 'select a.*,
              b.`nama` as `nama_jalur_pengiriman`,
              count(c.`id_penjualan`) as `total_penjualan`
              ' . $secondSelectorText . '
            from `user` a
              left join `jalur_pengiriman` b on a.`id_jalur_pengiriman` = b.`id_jalur_pengiriman`
              left join `penjualan` c on a.`id_user` = c.`id_user`
            where a.`role` = \'pelanggan\' ' . $whereClauseText . '
            group by a.`id_user`
            order by ' . $orderKeyText . ' ' . $orderValueText . ', a.`nama` asc
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

  public function getDataByIdJalurPengiriman($id_jalur_pengiriman, $filter, $orderKey, $orderValue) {
    $whereClause = array();
    $whereClauseText = '';
    $secondSelectorText = '';
    $orderKeyText = $orderKey;
    $orderValueText = $orderValue;

    /* Filter: fulltext `nama` */
    if ($filter['search'] != '') {
      $whereClause[] = ' match(a.`nama`, a.`no_telp`, a.`alamat`) against(\'' . $filter['search'] . '\') ';
      $secondSelectorText = ', match(a.`nama`, a.`no_telp`, a.`alamat`) against(\'' . $filter['search'] . '\') as `relevance` ';
      if (!isset($_GET['orderkey'])) {
        $orderKeyText = '`relevance`';
        $orderKeyValue = 'desc';
      }
    }

    if (count($whereClause) > 0) {
      $whereClauseText = ' and ' . implode(' and ', $whereClause);
    }
    
    $sql = 'select a.*,
              b.`nama` as `nama_jalur_pengiriman`,
              count(c.`id_penjualan`) as `total_penjualan`
              ' . $secondSelectorText . '
            from `user` a
              left join `jalur_pengiriman` b on a.`id_jalur_pengiriman` = b.`id_jalur_pengiriman`
              left join `penjualan` c on a.`id_user` = c.`id_user`
            where a.`role` = \'pelanggan\' and `username` is not null ' . $whereClauseText . ' and a.`id_jalur_pengiriman` = :id_jalur_pengiriman
            group by a.`id_user`
            order by ' . $orderKeyText . ' ' . $orderValueText . ', a.`nama` asc
            ';
    
    $this->setSql($sql);
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_jalur_pengiriman', $id_jalur_pengiriman);
    
    try {
      $stmt->execute();
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    } catch(\PDOException $e) {
      $this->setErrorInfo($e->getMessage());
      $this->setErrorCode($e->getCode());
      return false;
    }
  }

  public function getPerujuk() {
    $sql = 'select *
            from `user`
            where `role` != \'pelanggan\'
            order by `nama` asc';
    
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
    $sql = 'select *
            from `user`
            where `id_user` = :id_user and `role` = \'pelanggan\'
            ';
            
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_user', $id);

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

    $digits = 3;
    $pin = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
    $password = hash('sha512', 'pass');
    $role = 'pelanggan';

    $sql = 'insert into `user`
            (`id_jalur_pengiriman`, `role`, `username`, `password`, `nama`, `no_telp`, `alamat`, `pin`, `harga_satuan`, `metode_pembayaran`, `bonus`, `id_user_perujuk_1`, `id_user_perujuk_2`)
            values(:id_jalur_pengiriman, :role, :username, :password, :nama, :no_telp, :alamat, :pin, :harga_satuan, :metode_pembayaran, :id_user_perujuk_1, :id_user_perujuk_2)';
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_jalur_pengiriman', $data['id_jalur_pengiriman']);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':username', $data['username']);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':nama', $data['nama']);
    $stmt->bindParam(':no_telp', $data['no_telp']);
    $stmt->bindParam(':alamat', $data['alamat']);
    $stmt->bindParam(':pin', $pin);
    $stmt->bindParam(':harga_satuan', $data['harga_satuan']);
    $stmt->bindParam(':metode_pembayaran', $data['metode_pembayaran']);
    $stmt->bindParam(':bonus', $data['bonus']);
    $stmt->bindParam(':id_user_perujuk_1', $data['id_user_perujuk_1']);
    $stmt->bindParam(':id_user_perujuk_2', $data['id_user_perujuk_2']);
    
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

  public function addCustomer($data) {

    $digits = 3;
    $pin = isset($data['pin']) ? $data['pin'] : str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
    $password = hash('sha512', 'pass');
    $role = 'pelanggan';

    $sql = 'insert into `user`
            (`id_jalur_pengiriman`, `role`, `password`, `nama`, `no_telp`, `alamat`, `pin`)
            values(:id_jalur_pengiriman, :role, :password, :nama, :no_telp, :alamat, :pin)';
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_jalur_pengiriman', $data['id_jalur_pengiriman']);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':nama', $data['nama']);
    $stmt->bindParam(':no_telp', $data['no_telp']);
    $stmt->bindParam(':alamat', $data['alamat']);
    $stmt->bindParam(':pin', $pin);
    
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

    $sql = 'update `user` set
            `id_jalur_pengiriman` = :id_jalur_pengiriman,
            `username` = :username,
            `nama` = :nama,
            `no_telp` = :no_telp,
            `alamat` = :alamat,
            `harga_satuan` = :harga_satuan,
            `metode_pembayaran` = :metode_pembayaran,
            `bonus` = :bonus,
            `id_user_perujuk_1` = :id_user_perujuk_1,
            `id_user_perujuk_2` = :id_user_perujuk_2
            where `id_user` = :id_user';

    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_jalur_pengiriman', $data['id_jalur_pengiriman']);
    $stmt->bindParam(':username', $data['username']);
    $stmt->bindParam(':nama', $data['nama']);
    $stmt->bindParam(':no_telp', $data['no_telp']);
    $stmt->bindParam(':alamat', $data['alamat']);
    $stmt->bindParam(':harga_satuan', $data['harga_satuan']);
    $stmt->bindParam(':metode_pembayaran', $data['metode_pembayaran']);
    $stmt->bindParam(':bonus', $data['bonus']);
    $stmt->bindParam(':id_user_perujuk_1', $data['id_user_perujuk_1']);
    $stmt->bindParam(':id_user_perujuk_2', $data['id_user_perujuk_2']);
    $stmt->bindParam(':id_user', $id);
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
    $sql = 'delete from `user` where `id_user` = :id_user and `role` = \'pelanggan\'';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id_user', $id);
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