<?php

namespace App\Controller;

class Pelanggan extends \App\Core\Controller {

  public function index() {

    /* Admin only */
    if ($_SESSION['role'] != 'admin') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $filter = array();
    $filter['search'] = isset($_GET['s']) ? \App\Core\Utilities::sanitizeDBInput($_GET['s']) : '';

    $orderKey = isset($_GET['orderkey']) ? \App\Core\Utilities::sanitizeDBInput($_GET['orderkey']) : 'id_user';
    $orderValue = isset($_GET['ordervalue']) ? \App\Core\Utilities::sanitizeDBInput($_GET['ordervalue']) : 'desc';

    $page = isset($_GET['page']) ? (int)$_GET['page'] : '';
    $pelanggan = $this->model('Pelanggan');
    $data = array();
    $data['doc_title'] = 'Pelanggan';
    $data['pelanggan'] = $pelanggan->getPaginatedData(\App\Core\Paging::getLimit($page), $filter, $orderKey, $orderValue);

    $data['total_data'] = \App\Core\Paging::getTotalData($pelanggan->getSql());
    $data['paging'] = \App\Core\Paging::getLinks($data['total_data'], $page);
    
    $last_page = \App\Core\Paging::getLastPage();
    if ((int) $page > (int) $last_page) {
      header('location:?page=' . $last_page);
      die();
    }

    $data['filter'] = $filter;
    $data['order'] = \App\Core\Utilities::getOrderLinks(['nama', 'nama_jalur_pengiriman', 'alamat']);

    \App\Core\Sidebar::setActiveIcon('pelanggan')::setActiveLink('pelanggan');
    $this->show('pelanggan/daftar', $data);
    
  }

  public function form($id = 0) {

    /* Admin only */
    if ($_SESSION['role'] != 'admin') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $pelanggan = $this->model('Pelanggan');
    $jalurPengiriman = $this->model('JalurPengiriman');

    $data = array();
    $data['id_user'] = $id;
    $data['jalur_pengiriman'] = $jalurPengiriman->getAllData();
    $data['perujuk'] = $pelanggan->getPerujuk();
    $data['error'] = array();
    $data['default'] = array(
      'id_jalur_pengiriman' => '',
      'nama' => '',
      'username' => '',
      'no_telp' => '',
      'alamat' => '',
      'harga_satuan' => 0,
      'metode_pembayaran' => '',
      'id_user_perujuk_1' => '',
      'id_user_perujuk_2' => '',
    );

    $currentData = array();
    if ($id > 0) {
      $currentData = $pelanggan->getDataById($id);
      if ($currentData) {
        $data['default']['id_jalur_pengiriman'] = $currentData['id_jalur_pengiriman'];
        $data['default']['nama'] = $currentData['nama'];
        $data['default']['username'] = $currentData['username'];
        $data['default']['no_telp'] = $currentData['no_telp'];
        $data['default']['alamat'] = $currentData['alamat'];
        $data['default']['harga_satuan'] = $currentData['harga_satuan'];
        $data['default']['metode_pembayaran'] = $currentData['metode_pembayaran'];
        $data['default']['id_user_perujuk_1'] = $currentData['id_user_perujuk_1'];
        $data['default']['id_user_perujuk_2'] = $currentData['id_user_perujuk_2'];
      }
      else {
        header('HTTP/1.0 403 Forbidden');
        die();
      }
    }

    if (isset($_POST['submit'])) {
      
      $set = array();
      $set['id_jalur_pengiriman'] = isset($_POST['id_jalur_pengiriman']) ? trim($_POST['id_jalur_pengiriman']) : '';
      $set['nama'] = isset($_POST['nama']) ? trim($_POST['nama']) : '';
      $set['username'] = isset($_POST['username']) ? trim($_POST['username']) : '';
      $set['no_telp'] = isset($_POST['no_telp']) ? trim($_POST['no_telp']) : '';
      $set['alamat'] = isset($_POST['alamat']) ? trim($_POST['alamat']) : '';
      $set['harga_satuan'] = isset($_POST['harga_satuan']) ? \App\Core\Utilities::numbersOnly(trim($_POST['harga_satuan'])) : '';
      $set['metode_pembayaran'] = isset($_POST['metode_pembayaran']) ? trim($_POST['metode_pembayaran']) : '';
      $set['id_user_perujuk_1'] = isset($_POST['id_user_perujuk_1']) && trim($_POST['id_user_perujuk_1']) != '' ? trim($_POST['id_user_perujuk_1']) : null;
      $set['id_user_perujuk_2'] = isset($_POST['id_user_perujuk_2']) && trim($_POST['id_user_perujuk_2']) != ''? trim($_POST['id_user_perujuk_2']) : null;
      
      if ($set['id_jalur_pengiriman'] == '') {
        $data['error']['id_jalur_pengiriman'] = 'Jalur pengiriman harus diisi';
      }

      if ($set['nama'] == '') {
        $data['error']['nama'] = 'Nama harus diisi';
      }

      if ($set['username'] == '') {
        $data['error']['username'] = 'Username harus diisi';
      }

      if ($set['no_telp'] == '') {
        $data['error']['no_telp'] = 'No. telp harus diisi';
      }

      if ($set['alamat'] == '') {
        $data['error']['alamat'] = 'Alamat harus diisi';
      }

      if ($set['harga_satuan'] == '') {
        $data['error']['harga_satuan'] = 'Harga satuan harus diisi';
      }

      if ($set['metode_pembayaran'] == '') {
        $data['error']['metode_pembayaran'] = 'Metode pembayaran harus diisi';
      }

      $data['default']['id_jalur_pengiriman'] = $set['id_jalur_pengiriman'];
      $data['default']['nama'] = $set['nama'];
      $data['default']['username'] = $set['username'];
      $data['default']['no_telp'] = $set['no_telp'];
      $data['default']['alamat'] = $set['alamat'];
      $data['default']['harga_satuan'] = $set['harga_satuan'];
      $data['default']['metode_pembayaran'] = $set['metode_pembayaran'];
      $data['default']['id_user_perujuk_1'] = $set['id_user_perujuk_1'];
      $data['default']['id_user_perujuk_2'] = $set['id_user_perujuk_2'];
      
      if (count($data['error']) < 1) {
        if ($id > 0) {
          $dataIsEdited = $pelanggan->edit($id, $set);
          if ($dataIsEdited) {
            \App\Core\Flasher::set('pelanggan-form', '<p><strong>Pelanggan dengan nama `' . $set['nama'] . '` berhasil diedit</strong>.</p>', 'success');
            header('location:' . BASE_URL . '/pelanggan/form/' . $id);
            die();  
          }
          else {
            $data['error']['header'] = $pelanggan->getErrorCode() == '23000' ? 'Username <strong>`' . $set['username'] . '`</strong> telah dipakai' : $pelanggan->getErrorInfo();
          }
        }
        else {
          $dataIsAdded = $pelanggan->add($set);
          if ($dataIsAdded) {
            $lastInsertId = $pelanggan->getLastInsertId();
            \App\Core\Flasher::set('pelanggan-form', '<p><strong>Pelanggan dengan nama `' . $set['nama'] . '` berhasil disimpan</strong>. <br><a href="' . BASE_URL . '/pelanggan/form/' . $lastInsertId . '">Edit pelanggan ' . $set['nama'] . '</a> atau buat baru lagi dengan menggunakan form di bawah.</p>', 'success');
            header('location:' . BASE_URL . '/pelanggan/form');
            die();
          }
          else {
            $data['error']['header'] = $pelanggan->getErrorCode() == '23000' ? 'Username <strong>`' . $set['username'] . '`</strong> telah dipakai' : $pelanggan->getErrorInfo();
          }
        }
      }
    }

    $data['title'] = $id > 0 ? 'Edit Pelanggan' : 'Tambah Pelanggan';
    $data['button_label'] = $id > 0 ? 'Edit Data' : 'Tambahkan Data';

    \App\Core\Sidebar::setActiveIcon('pelanggan')::setActiveLink('pelanggan');
    $this->show('pelanggan/form', $data);
  }

  public function hapus($id = 0) {

    /* Admin only */
    if ($_SESSION['role'] != 'admin') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $id = (int) $id;
    if ($id < 1) {
      header('location:' . BASE_URL . '/pelanggan');
      die();
    }

    $pelanggan = $this->model('Pelanggan');
    $data = $pelanggan->getDataById($id);
    $data['doc_title'] = 'Hapus Pelanggan';
    
    if (isset($_POST['hapus']) && $data['role'] == 'pelanggan') {
      $pelanggan->delete($id);
      \App\Core\Flasher::set('pelanggan-daftar', '<p><strong>Pelanggan dengan nama `' . $data['nama'] . '` berhasil dihapus</strong>', 'success');
      header('location:' . BASE_URL . '/pelanggan');
      die();
    }

    \App\Core\Sidebar::setActiveIcon('pelanggan')::setActiveLink('pelanggan');
    $this->show('pelanggan/hapus', $data);

  }

}

?>