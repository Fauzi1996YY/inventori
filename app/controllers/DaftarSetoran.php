<?php

namespace App\Controller;

class DaftarSetoran extends \App\Core\Controller {

  public function index() {

    /* Admin only */
    if ($_SESSION['role'] != 'admin') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }
    
    $suratJalan = $this->model('SuratJalan');
    $penjualan = $this->model('Penjualan');
    $data = array(
      'doc_title' => 'Daftar Setoran'
    );
    
    $data['tanggal'] = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('d', strtotime('-1 days'));
    $data['bulan'] = isset($_GET['bulan']) ? $_GET['bulan'] : date('m', strtotime('-1 days'));
    $data['tahun'] = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y', strtotime('-1 days'));
    
    $data['setoran'] = $suratJalan->getSetoranPerDate(date($data['tahun'] . '-' . $data['bulan'] . '-' . $data['tanggal']));
    $data['minmax'] = $penjualan->getMinMaxYear();

    \App\Core\Sidebar::setActiveIcon('daftar-setoran')::setActiveLink('daftar-setoran');
    $this->show('daftar-setoran/daftar', $data);
    
  }

  public function detail($id_surat_jalan) {
    /* Admin only */
    if ($_SESSION['role'] != 'admin') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $suratJalan = $this->model('SuratJalan');
    $operasional = $this->model('Operasional');
    $kasbon = $this->model('Kasbon');

    $data = array(
      'doc_title' => 'Detail Setoran',
      'qs_back_to_list' => isset($_GET['tanggal']) && isset($_GET['bulan']) & isset($_GET['tahun']) ? '?tanggal=' . $_GET['tanggal'] . '&bulan=' . $_GET['bulan'] . '&tahun=' . $_GET['tahun'] : ''
    );

    $data['surat_jalan'] = $suratJalan->getDataById($id_surat_jalan);
    if (!$data['surat_jalan']) {
      header('HTTP/1.0 404 Not Found');
      die();
    }

    $data['biaya_operasional'] = $operasional->getAllData($id_surat_jalan);
    $data['total_biaya_operasional'] = 0;
    foreach ($data['biaya_operasional'] as $k => $v) {
      $data['total_biaya_operasional']+= $v['jumlah'];
    }

    $data['kasbon'] = $kasbon->getAllData($id_surat_jalan);
    $data['total_kasbon'] = 0;
    foreach ($data['kasbon'] as $k => $v) {
      $data['total_kasbon']+= $v['jumlah'];
    }

    \App\Core\Sidebar::setActiveIcon('daftar-setoran')::setActiveLink('daftar-setoran');
    $this->show('daftar-setoran/detail', $data);
  }

}

?>