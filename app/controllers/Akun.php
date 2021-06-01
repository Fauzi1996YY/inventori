<?php

namespace App\Controller;

class Akun extends \App\Core\Controller {

  public function index() {
    
    $user = $this->model('User');

    $data = array();
    $data['error'] = array();
    $data['doc_title'] = 'Edit Akun';

    $currentData = array();
    $currentData = $user->getDataById($_SESSION['id_user']);
    if ($currentData) {
      $data['default'] = array(
        'nama' => $currentData['nama'],
        'alamat' => $currentData['alamat'],
        'no_telp' => $currentData['no_telp']
      );
    }
    else {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    if (isset($_POST['submit'])) {
      $set = array();
      $set['password'] = isset($_POST['password']) ? $_POST['password'] : '';
      $set['repassword'] = isset($_POST['repassword']) ? $_POST['repassword'] : '';
      $set['nama'] = isset($_POST['nama']) ? trim($_POST['nama']) : '';
      $set['alamat'] = isset($_POST['alamat']) ? trim($_POST['alamat']) : '';
      $set['no_telp'] = isset($_POST['no_telp']) ? trim($_POST['no_telp']) : '';

      if ($set['password'] != '' && $set['password'] != $set['repassword']) {
        $data['error']['repassword'] = 'Ketik ulang password harus sama';
      }

      if ($set['nama'] == '') {
        $data['error']['nama'] = 'Nama harus diisi';
      }
      if ($set['no_telp'] == '') {
        $data['error']['no_telp'] = 'No telepon harus diisi';
      }

      $data['default'] = array(
        'nama' => $set['nama'],
        'alamat' => $set['alamat'],
        'no_telp' => $set['no_telp']
      );
    
      if (count($data['error']) < 1) {
        
        $set['password'] = trim($_POST['repassword']) == '' ? $currentData['password'] : hash('sha512', $_POST['repassword']);
        $akunEdited = $user->editAkun($set);
        if ($akunEdited) {
          \App\Core\Flasher::set('akun-form', '<p><strong>Data anda berhasil diedit</strong></p>', 'success');
          header('location:' . BASE_URL . '/akun');
          die();  
        }
        else {
          $data['error']['header'] = $user->getErrorInfo();
        }
      }
    }

    $data['title'] = 'Edit Akun';
    $data['button_label'] = 'Edit Data';

    \App\Core\Sidebar::setActiveIcon('akun')::setActiveLink('akun');
    $this->show('akun/form', $data);
  }

}

?>