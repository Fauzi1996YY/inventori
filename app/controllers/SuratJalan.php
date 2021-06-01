<?php

namespace App\Controller;

class SuratJalan extends \App\Core\Controller {

  public function index() {

    /* Admin only */
    if ($_SESSION['role'] != 'admin') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $suratJalan = $this->model('SuratJalan');
    $data = array();
    $data['doc_title'] = 'Surat Jalan Hari Ini';
    $data['surat_jalan'] = $suratJalan->getTodaysAndActiveData();
    
    \App\Core\Sidebar::setActiveIcon('surat-jalan')::setActiveLink('surat-jalan');
    $this->show('surat-jalan/daftar', $data);
    
  }

  public function form($id = 0) {

    /* Admin only */
    if ($_SESSION['role'] != 'admin') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $suratJalan = $this->model('SuratJalan');
    $jalurPengiriman = $this->model('JalurPengiriman');
    $sopir = $this->model('Sopir');

    $data = array();
    $data['id_surat_jalan'] = $id;
    $data['jalur_pengiriman'] = $jalurPengiriman->getTodaysInactive($id);
    $data['sopir'] = $sopir->getTodaysInactive($id);
    $data['error'] = array();
    $data['default'] = array(
      'id_jalur_pengiriman' => '',
      'id_user_1' => '',
      'id_user_2' => ''
    );

    $currentData = array();
    if ($id > 0) {
      $currentData = $suratJalan->getDataById($id);
      if ($currentData) {
        $data['default']['id_jalur_pengiriman'] = $currentData['id_jalur_pengiriman'];
        $data['default']['id_user_1'] = $currentData['id_user_1'];
        $data['default']['id_user_2'] = $currentData['id_user_2'];
      }
      else {
        header('HTTP/1.0 403 Forbidden');
        die();
      }
    }

    if (isset($_POST['submit'])) {
      $set = array();
      $set['id_jalur_pengiriman'] = isset($_POST['id_jalur_pengiriman']) ? trim($_POST['id_jalur_pengiriman']) : '';
      $set['id_user_1'] = isset($_POST['id_user_1']) ? trim($_POST['id_user_1']) : '';
      $set['id_user_2'] = isset($_POST['id_user_2']) && trim($_POST['id_user_2']) != '' ? trim($_POST['id_user_2']) : null;
      
      if ($set['id_jalur_pengiriman'] == '') {
        $data['error']['id_jalur_pengiriman'] = 'Jalur pengiriman harus diisi';
      }

      if ($set['id_user_1'] == '') {
        $data['error']['id_user_1'] = 'Sopir 1 harus diisi';
      }

      $data['default']['id_jalur_pengiriman'] = $set['id_jalur_pengiriman'];
      $data['default']['id_user_1'] = $set['id_user_1'];
      $data['default']['id_user_2'] = $set['id_user_2'];
      
      if (count($data['error']) < 1) {
        if ($id > 0) {
          $dataIsEdited = $suratJalan->edit($id, $set);
          if ($dataIsEdited) {
            \App\Core\Flasher::set('surat-jalan-form', '<p><strong>Surat jalan berhasil diedit</strong>.</p>', 'success');
            header('location:' . BASE_URL . '/surat-jalan/form/' . $id);
            die();  
          }
          else {
            $data['error']['header'] = $suratJalan->getErrorInfo();
          }
        }
        else {
          $dataIsAdded = $suratJalan->add($set);
          if ($dataIsAdded) {
            $lastInsertId = $suratJalan->getLastInsertId();
            \App\Core\Flasher::set('surat-jalan-form', '<p><strong>Surat jalan berhasil disimpan</strong>. <br><a href="' . BASE_URL . '/surat-jalan/form/' . $lastInsertId . '">Edit surat jalan ini</a> atau buat baru lagi dengan menggunakan form di bawah.</p>', 'success');
            header('location:' . BASE_URL . '/surat-jalan/form');
            die();
          }
          else {
            $data['error']['header'] = $suratJalan->getErrorInfo();
          }
        }
      }
    }

    $data['title'] = $id > 0 ? 'Edit Surat Jalan' : 'Tambah Surat Jalan';
    $data['button_label'] = $id > 0 ? 'Edit Data' : 'Tambahkan Data';

    \App\Core\Sidebar::setActiveIcon('surat-jalan')::setActiveLink('surat-jalan');
    $this->show('surat-jalan/form', $data);
  }

  public function hapus($id = 0) {

    /* Admin only */
    if ($_SESSION['role'] != 'admin') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $id = (int) $id;
    if ($id < 1) {
      header('location:' . BASE_URL . '/surat-jalan');
      die();
    }

    $suratJalan = $this->model('SuratJalan');
    $data = $suratJalan->getDataById($id);
    $data['doc_title'] = 'Hapus Surat Jalan';
    
    if (isset($_POST['hapus']) && $data['muatan_aktif'] < 1) {
      $suratJalan->delete($id);
      \App\Core\Flasher::set('surat-jalan-daftar', '<p><strong>Surat jalan untuk jalur pengiriman <strong>`' . $data['nama_jalur_pengiriman'] . '`</strong> berhasil dihapus</strong>', 'success');
      header('location:' . BASE_URL . '/surat-jalan');
      die();
    }

    \App\Core\Sidebar::setActiveIcon('surat-jalan')::setActiveLink('surat-jalan');
    $this->show('surat-jalan/hapus', $data);

  }

}

?>