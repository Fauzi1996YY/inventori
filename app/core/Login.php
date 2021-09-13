<?php

namespace App\Core;

class Login {

  public static function userIsLoggedIn() {

    $userIsLoggedIn = false;
    
    if (isset($_SESSION['email']) && isset($_SESSION['password']) && isset($_SESSION['id_user']) && isset($_SESSION['role'])) {
      
      $db = Database::getPDO();
      $sql = 'select * from `user` where `id_user` = :id_user and `email` = :email and `password` = :password and `role` = :role';
      $stmt = $db->prepare($sql);
      $stmt->bindParam(':id_user', $_SESSION['id_user']);
      $stmt->bindParam(':email', $_SESSION['email']);
      $stmt->bindParam(':password', $_SESSION['password']);
      $stmt->bindParam(':role', $_SESSION['role']);
      $stmt->execute();
      
      
      if ($stmt->fetch(\PDO::FETCH_ASSOC)) {
        return true;
      }
    }
    return false;
  
  }

  public static function logUserIn($email, $password) {
    
    $db = Database::getPDO();
    $password = hash('sha512', $password);
    $sql = 'select * from `user` where `email` = :email and `password` = :password';
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, \PDO::PARAM_STR);
    $stmt->execute();
    $res = $stmt->fetch(\PDO::FETCH_ASSOC);
    
    if ($res) {
      $_SESSION['id_user'] = $res['id_user'];
      $_SESSION['email'] = $res['email'];
      $_SESSION['password'] = $res['password'];
      $_SESSION['role'] = $res['role'];
      return true;
    }
    return false;
  }

}

?>