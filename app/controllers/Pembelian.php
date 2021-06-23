<?php

namespace App\Controller;

class Pembelian extends \App\Core\Controller {

  public function index() {

    /* Admin only */
    if ($_SESSION['role'] != 'pelanggan') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $penjualan = $this->model('Penjualan');
    $data = array(
      'doc_title' => 'Daftar Pembelian'
    );

    $data['bulan'] = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
    $data['tahun'] = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
    
    $data['penjualan'] = $penjualan->getPenjualanByDatePerUser($data['bulan'], $data['tahun']);
    $data['minmax'] = $penjualan->getMinMaxYear();

    \App\Core\Sidebar::setActiveIcon('pembelian')::setActiveLink('pembelian');
    $this->show('pembelian/daftar', $data);
    
  }

}

?>