<?php

namespace App\Controller;

class Rekening extends \App\Core\Controller {

  public function index() {

    /* Admin only */
    if ($_SESSION['role'] != 'admin') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $rekening = $this->model('Rekening');

    $data = array();
    $data['doc_title'] = 'Daftar Rekening';

    $page = isset($_GET['page']) ? (int)$_GET['page'] : '';
    $data['rekening'] = $rekening->getPaginatedData(\App\Core\Paging::getLimit($page));
    $data['total_data'] = \App\Core\Paging::getTotalData($rekening->getSql());
    $data['paging'] = \App\Core\Paging::getLinks($data['total_data'], $page);

    $last_page = \App\Core\Paging::getLastPage();
    if ((int) $page > (int) $last_page) {
      header('location:?page=' . $last_page);
      die();
    }

    \App\Core\Sidebar::setActiveIcon('rekening')::setActiveLink('rekening');
    $this->show('rekening/daftar', $data);
    
  }

  public function form($id = 0) {

    /* Admin only */
    if ($_SESSION['role'] != 'admin') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $rekening = $this->model('Rekening');

    $data = array();
    $data['id_rekening'] = $id;
    $data['error'] = array();
    $data['default'] = array(
      'bank' => '',
      'kantor_cabang' => '',
      'nomor_rekening' => '',
      'nama_pemilik_rekening' => '',
      'jenis_rekening' => ''
    );

    $currentData = array();
    if ($id > 0) {
      $currentData = $rekening->getDataById($id);
      if ($currentData) {
        $data['default']['bank'] = $currentData['bank'];
        $data['default']['kantor_cabang'] = $currentData['kantor_cabang'];
        $data['default']['nomor_rekening'] = $currentData['nomor_rekening'];
        $data['default']['nama_pemilik_rekening'] = $currentData['nama_pemilik_rekening'];
        $data['default']['jenis_rekening'] = $currentData['jenis_rekening'];
      }
      else {
        header('HTTP/1.0 403 Forbidden');
        die();
      }
    }

    if (isset($_POST['submit'])) {
      $set = array();
      $set['bank'] = isset($_POST['bank']) ? trim($_POST['bank']) : '';
      $set['kantor_cabang'] = isset($_POST['kantor_cabang']) ? trim($_POST['kantor_cabang']) : '';
      $set['nomor_rekening'] = isset($_POST['nomor_rekening']) ? trim($_POST['nomor_rekening']) : '';
      $set['nama_pemilik_rekening'] = isset($_POST['nama_pemilik_rekening']) ? trim($_POST['nama_pemilik_rekening']) : '';
      $set['jenis_rekening'] = isset($_POST['jenis_rekening']) ? trim($_POST['jenis_rekening']) : '';
      
      if ($set['bank'] == '') {
        $data['error']['bank'] = 'Bank harus diisi';
      }

      if ($set['kantor_cabang'] == '') {
        $data['error']['kantor_cabang'] = 'Kantor cabang harus diisi';
      }

      if ($set['nomor_rekening'] == '') {
        $data['error']['nomor_rekening'] = 'Nomor rekening harus diisi';
      }

      if ($set['nama_pemilik_rekening'] == '') {
        $data['error']['nama_pemilik_rekening'] = 'Nama pemilik rekening harus diisi';
      }

      if ($set['jenis_rekening'] == '') {
        $data['error']['jenis_rekening'] = 'Jenis rekening harus diisi';
      }

      $data['default']['bank'] = $set['bank'];
      $data['default']['kantor_cabang'] = $set['kantor_cabang'];
      $data['default']['nomor_rekening'] = $set['nomor_rekening'];
      $data['default']['nama_pemilik_rekening'] = $set['nama_pemilik_rekening'];
      $data['default']['jenis_rekening'] = $set['jenis_rekening'];
      
      if (count($data['error']) < 1) {
        if ($id > 0) {
          $dataIsEdited = $rekening->edit($id, $set);
          if ($dataIsEdited) {
            \App\Core\Flasher::set('rekening-form', '<p><strong>Rekening dengan jenis `' . $set['jenis_rekening'] . '` berhasil diedit</strong>.</p>', 'success');
            header('location:' . BASE_URL . '/rekening/form/' . $id);
            die();  
          }
          else {
            $data['error']['header'] = $rekening->getErrorCode() == '23000' ? 'Jenis rekening <strong>`' . $set['jenis_rekening'] . '`</strong> telah dipakai' : $rekening->getErrorInfo();
            $data['error']['jenis_rekening'] = 'Jenis rekening telah dipakai';
          }
        }
        else {
          $dataIsAdded = $rekening->add($set);
          if ($dataIsAdded) {
            $lastInsertId = $rekening->getLastInsertId();
            \App\Core\Flasher::set('rekening-form', '<p><strong>Rekening baru dengan jenis `' . $set['jenis_rekening'] . '` berhasil disimpan</strong>. <br><a href="' . BASE_URL . '/rekening/form/' . $lastInsertId . '">Edit rekening ' . $set['jenis_rekening'] . '</a> atau buat baru lagi dengan menggunakan form di bawah.</p>', 'success');
            header('location:' . BASE_URL . '/rekening/form');
            die();
          }
          else {
            $data['error']['header'] = $rekening->getErrorCode() == '23000' ? 'Jenis rekening <strong>`' . $set['jenis_rekening'] . '`</strong> telah dipakai' : $rekening->getErrorInfo();
            $data['error']['jenis_rekening'] = 'Jenis rekening telah dipakai';
          }
        }
      }
    }

    $data['title'] = $id > 0 ? 'Edit Rekening' : 'Tambah Rekening';
    $data['button_label'] = $id > 0 ? 'Edit Data' : 'Tambahkan Data';

    \App\Core\Sidebar::setActiveIcon('rekening')::setActiveLink('rekening');
    $this->show('rekening/form', $data);
  }

  public function hapus($id = 0) {

    /* Admin only */
    if ($_SESSION['role'] != 'admin') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $id = (int) $id;
    if ($id < 1) {
      header('location:' . BASE_URL . '/rekening');
      die();
    }

    $rekening = $this->model('Rekening');
    $data = $rekening->getDataById($id);
    $data['doc_title'] = 'Hapus Rekening';
    
    if (isset($_POST['hapus']) && $data['total_jurnal_umum'] < 1) {
      $rekening->delete($id);
      \App\Core\Flasher::set('rekening-daftar', '<p><strong>Rekening dengan jenis `' . $data['jenis_rekening'] . '` berhasil dihapus</strong>', 'success');
      header('location:' . BASE_URL . '/rekening');
      die();
    }

    \App\Core\Sidebar::setActiveIcon('rekening')::setActiveLink('rekening');
    $this->show('rekening/hapus', $data);

  }

}

?>