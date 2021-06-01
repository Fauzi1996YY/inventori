<?php

namespace App\Controller;

class JalurPengiriman extends \App\Core\Controller {

  public function index() {

    /* Admin only */
    if ($_SESSION['role'] != 'admin') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $jalurPengiriman = $this->model('JalurPengiriman');

    $data = array();
    $data['doc_title'] = 'Jalur Pengiriman';
    $data['jalur-pengiriman'] = $jalurPengiriman->getAllData();

    \App\Core\Sidebar::setActiveIcon('jalur-pengiriman')::setActiveLink('jalur-pengiriman');
    $this->show('jalur-pengiriman/daftar', $data);
    
  }

  public function form($id = 0) {

    /* Admin only */
    if ($_SESSION['role'] != 'admin') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $jalurPengiriman = $this->model('JalurPengiriman');

    $data = array();
    $data['id_jalur_pengiriman'] = $id;
    $data['error'] = array();
    $data['default'] = array(
      'nama' => ''
    );

    $currentData = array();
    if ($id > 0) {
      $currentData = $jalurPengiriman->getDataById($id);
      if ($currentData) {
        $data['default']['nama'] = $currentData['nama'];
      }
      else {
        header('HTTP/1.0 403 Forbidden');
        die();
      }
    }

    if (isset($_POST['submit'])) {
      $set = array();
      $set['nama'] = isset($_POST['nama']) ? trim($_POST['nama']) : '';
      
      if ($set['nama'] == '') {
        $data['error']['nama'] = 'Nama harus diisi';
      }

      $data['default']['nama'] = $set['nama'];
      
      if (count($data['error']) < 1) {
        if ($id > 0) {
          $dataIsEdited = $jalurPengiriman->edit($id, $set);
          if ($dataIsEdited) {
            \App\Core\Flasher::set('jalur-pengiriman-form', '<p><strong>Jalur pengiriman dengan nama `' . $set['nama'] . '` berhasil diedit</strong>.</p>', 'success');
            header('location:' . BASE_URL . '/jalur-pengiriman/form/' . $id);
            die();  
          }
          else {
            $data['error']['header'] = $jalurPengiriman->getErrorCode() == '23000' ? 'Nama <strong>`' . $set['nama'] . '`</strong> telah dipakai' : $jalurPengiriman->getErrorInfo();
          }
        }
        else {
          $dataIsAdded = $jalurPengiriman->add($set);
          if ($dataIsAdded) {
            $lastInsertId = $jalurPengiriman->getLastInsertId();
            \App\Core\Flasher::set('jalur-pengiriman-form', '<p><strong>Jalur baru dengan nama `' . $set['nama'] . '` berhasil disimpan</strong>. <br><a href="' . BASE_URL . '/jalur-pengiriman/form/' . $lastInsertId . '">Edit jalur ' . $set['nama'] . '</a> atau buat baru lagi dengan menggunakan form di bawah.</p>', 'success');
            header('location:' . BASE_URL . '/jalur-pengiriman/form');
            die();
          }
          else {
            $data['error']['header'] = $jalurPengiriman->getErrorCode() == '23000' ? 'Nama <strong>`' . $set['nama'] . '`</strong> telah dipakai' : $jalurPengiriman->getErrorInfo();
          }
        }
      }
    }

    $data['title'] = $id > 0 ? 'Edit Jalur Pengiriman' : 'Tambah Jalur Pengiriman';
    $data['button_label'] = $id > 0 ? 'Edit Data' : 'Tambahkan Data';

    \App\Core\Sidebar::setActiveIcon('jalur-pengiriman')::setActiveLink('jalur-pengiriman');
    $this->show('jalur-pengiriman/form', $data);
  }

  public function hapus($id = 0) {

    /* Admin only */
    if ($_SESSION['role'] != 'admin') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $id = (int) $id;
    if ($id < 1) {
      header('location:' . BASE_URL . '/jalur-pengiriman');
      die();
    }

    $jalurPengiriman = $this->model('JalurPengiriman');
    $data = $jalurPengiriman->getDataById($id);
    $data['doc_title'] = 'Hapus Jalur Pengiriman';
    
    if (isset($_POST['hapus']) && $data['total_surat_jalan'] < 1) {
      $jalurPengiriman->delete($id);
      \App\Core\Flasher::set('jalur-pengiriman-daftar', '<p><strong>Jalur pengiriman dengan nama `' . $data['nama'] . '` berhasil dihapus</strong>', 'success');
      header('location:' . BASE_URL . '/jalur-pengiriman');
      die();
    }

    \App\Core\Sidebar::setActiveIcon('jalur-pengiriman')::setActiveLink('jalur-pengiriman');
    $this->show('jalur-pengiriman/hapus', $data);

  }

}

?>