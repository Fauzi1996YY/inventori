<?php

namespace App\Controller;

class Penjualan extends \App\Core\Controller {

  public function index($id_surat_jalan = 0) {

    /* Admin only */
    if ($_SESSION['role'] != 'admin') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $penjualan = $this->model('Penjualan');
    $data = array();
    
    if ($id_surat_jalan > 0) {
      $suratJalan = $this->model('SuratJalan');
      $data['surat_jalan'] = $suratJalan->getDataById($id_surat_jalan);

      if (!$data['surat_jalan']) {
        /* Invalid surat jalan */
        header('HTTP/1.0 404 Not Found');
        die();
      }
      $data['doc_title'] = 'Detail Penjualan Per Surat Jalan';
      $data['detail'] = $penjualan->getDataByIdSuratJalan($id_surat_jalan);

      \App\Core\Sidebar::setActiveIcon('penjualan')::setActiveLink('penjualan');
      $this->show('penjualan/detail', $data);
      return;
    }
    
    $data['doc_title'] = 'Surat Jalan Hari Ini';
    
    $data['tanggal'] = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('d');
    $data['bulan'] = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
    $data['tahun'] = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
    
    $data['penjualan'] = $penjualan->getPenjualanByDate(date($data['tahun'] . '-' . $data['bulan'] . '-' . $data['tanggal']));
    $data['minmax'] = $penjualan->getMinMaxYear();

    
    \App\Core\Sidebar::setActiveIcon('penjualan')::setActiveLink('penjualan');
    $this->show('penjualan/daftar', $data);
    
  }
  
  public function tambah($id_surat_jalan = 0) {
    $this->form(0, $id_surat_jalan);
  }

  public function edit($id_penjualan = 0) {
    $this->form($id_penjualan, 0);
  }

  private function form($id_penjualan = 0, $id_surat_jalan = 0) {

    /* Admin only */
    if ($_SESSION['role'] != 'admin') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $suratJalan = $this->model('SuratJalan');
    $penjualan = $this->model('Penjualan');
    
    $data = array(
      'surat_jalan' => null,
      'penjualan' => null,
      'error' => array(),
      'default' => array(
        'es_tabung_besar' => 0,
        'es_tabung_kecil' => 0,
        'es_serut' => 0,
        'berat_total' => 0,
        'total_harga' => 0,
        'metode_pembayaran' => ''
      )
    );

    if ($id_surat_jalan > 0) {
      $data['surat_jalan'] = $suratJalan->getDataById($id_surat_jalan);
      if (!$data['surat_jalan']) {
        /* Invalid surat jalan */
        header('HTTP/1.0 404 Not Found');
        die();
      }
    }

    if ($id_penjualan > 0) {
      $data['penjualan'] = $penjualan->getDataById($id_penjualan);
      if (!$data['penjualan']) {
        header('HTTP/1.0 404 Not Found');
        die();
      }
    }

    /* Tambah */
    if ($data['surat_jalan']) {
      $data['text_header'] = 'Tambah Data Penjualan';
      $data['text_button'] = 'Tambah Data';
      $data['id_jalur_pengiriman'] = $data['surat_jalan']['id_jalur_pengiriman'];
      $data['nama_jalur'] = $data['surat_jalan']['nama_jalur_pengiriman'];
      $data['nama_sopir_1'] = $data['surat_jalan']['nama_sopir_1'];
      $data['nama_sopir_2'] = $data['surat_jalan']['nama_sopir_2'];
      $data['id_surat_jalan'] = $id_surat_jalan;
      $data['harga_satuan'] = 0;
      $data['metode_pembayaran'] = '';
      $data['metode_pembayaran_pembeli'] = '';
      $data['bonus'] = 0;
      
    }

    /* Edit */
    if ($data['penjualan']) {
      $data['default'] = array(
        'es_tabung_besar' => $data['penjualan']['es_tabung_besar'],
        'es_tabung_kecil' => $data['penjualan']['es_tabung_kecil'],
        'es_serut' => $data['penjualan']['es_serut'],
        'berat_total' => $data['penjualan']['berat_total'],
        'total_harga' => $data['penjualan']['total_harga'],
        'metode_pembayaran' => $data['penjualan']['metode_pembayaran']
      );
      
      $data['text_header'] = 'Edit Data Penjualan';
      $data['text_button'] = 'Edit Data';
      $data['id_jalur_pengiriman'] = $data['penjualan']['id_jalur_pengiriman'];
      $data['nama_jalur'] = $data['penjualan']['nama_jalur'];
      $data['nama_sopir_1'] = $data['penjualan']['nama_sopir_1'];
      $data['nama_sopir_2'] = $data['penjualan']['nama_sopir_2'];
      $data['id_surat_jalan'] = $data['penjualan']['id_surat_jalan'];
      $data['harga_satuan'] = $data['penjualan']['harga_satuan'];
      $data['metode_pembayaran'] = $data['penjualan']['metode_pembayaran'];
      $data['metode_pembayaran_pembeli'] = $data['penjualan']['metode_pembayaran_pembeli'];
      $data['bonus'] = $data['penjualan']['bonus'];
    }
    
    if (isset($_POST['submit'])) {
      $set = array();
      $set['id_user'] = isset($_POST['id_user']) ? trim($_POST['id_user']) : '';
      $set['es_tabung_besar'] = isset($_POST['es_tabung_besar']) ? trim($_POST['es_tabung_besar']) : '';
      $set['es_tabung_kecil'] = isset($_POST['es_tabung_kecil']) ? trim($_POST['es_tabung_kecil']) : '';
      $set['es_serut'] = isset($_POST['es_serut']) ? trim($_POST['es_serut']) : '';
      $set['berat_total'] = isset($_POST['berat_total']) ? trim($_POST['berat_total']) : '';
      $set['bonus_es_tabung_kecil'] = isset($_POST['bonus_es_tabung_kecil']) ? (int)trim($_POST['bonus_es_tabung_kecil']) : 0;
      $set['total_harga'] = isset($_POST['total_harga']) ? \App\Core\Utilities::numbersOnly(trim($_POST['total_harga'])) : '';
      $set['metode_pembayaran'] = isset($_POST['metode_pembayaran']) ? trim($_POST['metode_pembayaran']) : '';
      
      if ($id_surat_jalan > 0 && $set['id_user'] == '') {
        $data['error']['header'] = 'Nama pembeli harus diisi';
      }

      if ($set['es_tabung_besar'] < 1 && $set['es_tabung_kecil'] < 1 && $set['es_serut'] < 1){
        $data['error']['header'] = 'Salah satu barang harus diisi';
      }

      if ($set['es_tabung_besar'] == '') {
        $data['error']['es_tabung_besar'] = 'Es tabung besar harus diisi';
      }

      if ($set['es_tabung_kecil'] == '') {
        $data['error']['es_tabung_kecil'] = 'Es tabung kecil harus diisi';
      }

      if ($set['es_serut'] == '') {
        $data['error']['es_serut'] = 'Es serut harus diisi';
      }

      if ($set['total_harga'] == '') {
        $data['error']['total_harga'] = 'Total harga harus diisi';
      }
      else if ($set['total_harga'] < 1) {
        $data['error']['total_harga'] = 'Total harga harus diisi';
      }

      if ($set['metode_pembayaran'] == '') {
        $data['error']['metode_pembayaran'] = 'Metode pembayaran harus diisi';
      }

      $data['default']['es_tabung_besar'] = $set['es_tabung_besar'];
      $data['default']['es_tabung_kecil'] = $set['es_tabung_kecil'];
      $data['default']['es_serut'] = $set['es_serut'];
      $data['default']['berat_total'] = $set['berat_total'];
      $data['default']['total_harga'] = $set['total_harga'];
      $data['default']['metode_pembayaran'] = $set['metode_pembayaran'];
      
      if (count($data['error']) < 1) {

        /* Tambah */
        if ($id_surat_jalan > 0) {
          $set['id_user'] = $set['id_user'];
          $set['id_surat_jalan'] = $id_surat_jalan;
          $dataIsAdded = $penjualan->add($set);
          if ($dataIsAdded) {
            \App\Core\Flasher::set('penjualan-form', '<p><strong>Penjualan berhasil ditambah</strong>.</p>', 'success');
            header('location:' . BASE_URL . '/penjualan/tambah/' . $id_surat_jalan);
            die();
          }
          else {
            $data['error']['header'] = $penjualan->getErrorInfo();
          }
        }

        /* Edit */
        if ($id_penjualan > 0) {
          $set['id_penjualan'] = $id_penjualan;
          $dataIsEdited = $penjualan->edit($set);
          if ($dataIsEdited) {
            \App\Core\Flasher::set('penjualan-form', '<p><strong>Penjualan untuk `' . $data['penjualan']['nama_pembeli'] . '` berhasil dirubah</strong>.</p>', 'success');
            header('location:' . BASE_URL . '/penjualan/edit/' . $id_penjualan);
            die();
          }
          else {
            $data['error']['header'] = $penjualan->getErrorInfo();
          }
        }
      }
    }

    \App\Core\Sidebar::setActiveIcon('penjualan')::setActiveLink('penjualan');
    $this
      ->addJS('assets/js/search-pelanggan.js')
      ->addJS('assets/js/nota.js')
      ->show('penjualan/form', $data);

  }

  /* Todays sales for role: sopir */
  public function sopir() {
    /* Admin only */
    if ($_SESSION['role'] != 'sopir') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $penjualan = $this->model('Penjualan');
    $suratJalan = $this->model('SuratJalan');
    $forSopir = $suratJalan->getTodaysForSopir();
    $id_surat_jalan = $forSopir['id_surat_jalan'];

    $data = array();
    $data['surat_jalan'] = $suratJalan->getDataById($id_surat_jalan);

    if (!$data['surat_jalan']) {
      echo 'Penjualan tidak bisa dilihat karena surat jalan sudah selesai';
      die();
    }
    $data['doc_title'] = 'Detail Penjualan Per Surat Jalan';
    $data['detail'] = $penjualan->getDataByIdSuratJalan($id_surat_jalan);

    \App\Core\Sidebar::setActiveIcon('penjualan')::setActiveLink('penjualan');
    $this->show('penjualan/detail', $data);
  }

}

?>