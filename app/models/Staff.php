<?php

namespace App\Model;

class Staff extends \App\Core\Model {

  public function getAllData() {
    
    $sql = 'select *
            from `user`
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

  public function getPaginatedData($limit) {
    
    $sql = 'select *
            from `user`
            where `role` != \'admin\'
            order by `id_user`
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

  public function add($data) {
    
    $data['role'] = 'staff';
    $data['password'] = hash('sha512', 'pass');

    $sql = 'insert into `user` (`email`, `password`, `role`, `nama`)
            values(:email, :password, :role, :nama)';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':password', $data['password']);
    $stmt->bindParam(':nama', $data['nama']);
    $stmt->bindParam(':role', $data['role']);

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
    $sql = 'update `user` set `email` = :email, `nama` = :nama
            where `id_user` = :id_user';
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':nama', $data['nama']);
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
    $sql = 'delete from `user` where `id_user` = :id_user';
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