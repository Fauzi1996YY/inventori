<?php

namespace App\Controller;

class Logout extends \App\Core\Controller {

  public function index() {

    $_SESSION = array();
    header('location:' . BASE_URL);

  }
  
}

?>