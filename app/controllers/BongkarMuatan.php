<?php

namespace App\Controller;

class BongkarMuatan extends \App\Core\Controller {

  public function index() {

    /* Admin and Sopir only */
    if ($_SESSION['role'] != 'sopir') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }
    
    $muatan = $this->model('Muatan');
    $suratJalan = $this->model('SuratJalan');

    $data = array();
    $data['surat_jalan'] = $suratJalan->getTodaysForSopir();

    if (isset($_POST['bongkar'])) {
      $muatan->bongkarMuatanByIdSuratJalan($data['surat_jalan']['id_surat_jalan']);
      \App\Core\Flasher::set('distribusi-daftar', '<p><strong>Bongkar muatan berhasil diinisiasi</strong>.</p>', 'success');
      header('location:' . BASE_URL . '/distribusi/');
      die();
    }

    $data = array();
    \App\Core\Sidebar::setActiveIcon('distribusi')::setActiveLink('distribusi');
    $this->show('bongkar-sopir/notifikasi', $data);
    
  }

}

?>