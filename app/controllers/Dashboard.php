<?php

namespace App\Controller;

class Dashboard extends \App\Core\Controller {

  public function index() {
    
    $user = $this->model('User');

    $data = array();
    $data['error'] = array();
    $data['doc_title'] = 'Dashboard';

    \App\Core\Sidebar::setActiveIcon('dashboard')::setActiveLink('dashboard');
    $this->show('dashboard/index', $data);
  }

}

?>