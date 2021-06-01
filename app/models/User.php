<?php

namespace App\Model;

class User extends \App\Core\Model {

  public function getAllDataByRole($role) {
    
    $role = strtolower($role);

    $sql = 'select * from `user`
            where `role` = :role
            order by `nama` asc';
    
    $this->setSql($sql);
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':role', $role);
    
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
            where `id_user` = :id_user
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

  public function editAkun($data) {
    $sql = 'update `user` set `password` = :password, `nama` = :nama, `alamat` = :alamat, `no_telp` = :no_telp where `id_user` = :id_user';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':password', $data['password']);
    $stmt->bindParam(':nama', $data['nama']);
    $stmt->bindParam(':alamat', $data['alamat']);
    $stmt->bindParam(':no_telp', $data['no_telp']);
    $stmt->bindParam(':id_user', $_SESSION['id_user']);
    try {
      $stmt->execute();
      $_SESSION['password'] = $data['password'];
      return true;
    } catch(\PDOException $e) {
      $this->setErrorInfo($e->getMessage());
      $this->setErrorCode($e->getCode());
      return false;
    }
  }
}

?>