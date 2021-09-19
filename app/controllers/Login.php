<?php

namespace App\Controller;

class Login extends \App\Core\Controller {

  public function index() {

    $data = array();
    $data['error'] = '';
    $data['doc_title'] = 'Login';
    
    if (isset($_POST['login'])) {
      if (\App\Core\Login::logUserIn($_POST['email'], $_POST['password'])) {
        header('location:' . BASE_URL);
      }
      else {
        $data['error'] = 'Email or password is not valid';
      }
    }

    $this->setHeaderTemplate(null)->setFooterTemplate(null)->show('login/form', $data);
  }

}

?>