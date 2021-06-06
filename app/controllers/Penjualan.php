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

  public function form($id_penjualan = 0) {

    /* Admin only */
    if ($_SESSION['role'] != 'admin') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $penjualan = $this->model('Penjualan');
    $data = array();

    $data['penjualan'] = $penjualan->getDataById($id_penjualan);
    
    if (!$data['penjualan']) {
      header('HTTP/1.0 404 Not Found');
      die();
    }

    $data['error'] = array();
    $data['default'] = array(
      'es_tabung_besar' => $data['penjualan']['es_tabung_besar'],
      'es_tabung_kecil' => $data['penjualan']['es_tabung_kecil'],
      'es_serut' => $data['penjualan']['es_serut'],
      'berat_total' => $data['penjualan']['berat_total'],
      'total_harga' => $data['penjualan']['total_harga'],
      'metode_pembayaran' => $data['penjualan']['metode_pembayaran']
    );

    if (isset($_POST['submit'])) {
      $set = array();
      $set['es_tabung_besar'] = isset($_POST['es_tabung_besar']) ? trim($_POST['es_tabung_besar']) : '';
      $set['es_tabung_kecil'] = isset($_POST['es_tabung_kecil']) ? trim($_POST['es_tabung_kecil']) : '';
      $set['es_serut'] = isset($_POST['es_serut']) ? trim($_POST['es_serut']) : '';
      $set['berat_total'] = isset($_POST['berat_total']) ? trim($_POST['berat_total']) : '';
      $set['bonus_es_tabung_kecil'] = isset($_POST['bonus_es_tabung_kecil']) ? (int)trim($_POST['bonus_es_tabung_kecil']) : 0;
      $set['total_harga'] = isset($_POST['total_harga']) ? \App\Core\Utilities::numbersOnly(trim($_POST['total_harga'])) : '';
      $set['metode_pembayaran'] = isset($_POST['metode_pembayaran']) ? trim($_POST['metode_pembayaran']) : '';
      
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
        $set['id_penjualan'] = $id_penjualan;
        $dataIsEdited = $penjualan->edit($set);
        if ($dataIsEdited) {
          \App\Core\Flasher::set('penjualan-form', '<p><strong>Penjualan untuk `' . $data['penjualan']['nama_pembeli'] . '` berhasil dirubah</strong>.</p>', 'success');
          header('location:' . BASE_URL . '/penjualan/form/' . $id_penjualan);
          die();
        }
        else {
          $data['error']['header'] = $penjualan->getErrorInfo();
        }
      }
    }

    \App\Core\Sidebar::setActiveIcon('penjualan')::setActiveLink('penjualan');
    $this
      ->addJS('assets/js/nota.js')
      ->show('penjualan/form', $data);
  }

}

?>