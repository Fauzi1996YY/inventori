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

}

?>