<?php

namespace App\Controller;

class Distribusi extends \App\Core\Controller {

  public function index() {

    /* Sopir only */
    if ($_SESSION['role'] != 'sopir') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $filter = array();
    $filter['search'] = isset($_GET['s']) ? \App\Core\Utilities::sanitizeDBInput($_GET['s']) : '';

    $orderKey = isset($_GET['orderkey']) ? \App\Core\Utilities::sanitizeDBInput($_GET['orderkey']) : 'nama';
    $orderValue = isset($_GET['ordervalue']) ? \App\Core\Utilities::sanitizeDBInput($_GET['ordervalue']) : 'desc';

    $suratJalan = $this->model('SuratJalan');
    $pelanggan = $this->model('Pelanggan');
    $muatan = $this->model('Muatan');
    $penjualan = $this->model('Penjualan');

    $data = array();
    $data['doc_title'] = 'Distribusi Hari Ini';
    $data['surat_jalan'] = $suratJalan->getTodaysForSopir($_SESSION['id_user']);
    
    if (!$data['surat_jalan']) {
      \App\Core\Sidebar::setActiveIcon('distribusi')::setActiveLink('distribusi');
      $data['no_data_message'] = 'Belum ada surat jalan untuk hari ini. Hubungi admin untuk memproses surat jalan';
      $this->show('distribusi/no-data', $data);
      return;
    }

    if (isset($_POST['validasi_muatan'])) {
      $muatan->validateByIdSuratJalan($data['surat_jalan']['id_surat_jalan']);
      header('location:' . BASE_URL . '/distribusi/');
      die();
    }

    $data['muatan'] = $muatan->getDataByIdSuratJalan($data['surat_jalan']['id_surat_jalan']);
    
    if (count($data['muatan']) < 1 || $data['surat_jalan']['total_muatan'] == $data['surat_jalan']['total_muatan_tervalidasi']) {
      \App\Core\Sidebar::setActiveIcon('distribusi')::setActiveLink('distribusi');
      $data['no_data_message'] = 'Belum ada muatan untuk hari ini. Hubungi admin untuk memproses muatan';
      $this->show('distribusi/no-data', $data);
      return;
    }

    $data['sisa_muatan'] = array(
      'es_tabung_besar' => 0,
      'es_tabung_kecil' => 0,
      'es_serut' => 0
    );

    /* Sisa barang 1 = muatan - rusak */
    $data['invalidated_muatan'] = false;
    foreach ($data['muatan'] as $k => $v) {
      $data['sisa_muatan']['es_tabung_besar'] += $v['muatan_tabung_besar'];
      $data['sisa_muatan']['es_tabung_besar'] -= $v['kembali_tabung_besar'];
      $data['sisa_muatan']['es_tabung_besar'] -= $v['rusak_tabung_besar'];

      $data['sisa_muatan']['es_tabung_kecil'] += $v['muatan_tabung_kecil'];
      $data['sisa_muatan']['es_tabung_kecil'] -= $v['kembali_tabung_kecil'];
      $data['sisa_muatan']['es_tabung_kecil'] -= $v['rusak_tabung_kecil'];

      $data['sisa_muatan']['es_serut'] += $v['muatan_serut'];
      $data['sisa_muatan']['es_serut'] -= $v['kembali_serut'];
      $data['sisa_muatan']['es_serut'] -= $v['rusak_serut'];

      if ($v['validasi_muatan'] == 0) {
        $data['invalidated_muatan'] = true;
      }
    }

    /* Sisa barang 2 = sisa barang 1 - penjualan */
    $data['penjualan'] = $penjualan->getDataByIdSuratJalan($data['surat_jalan']['id_surat_jalan']);
    foreach ($data['penjualan'] as $k => $v) {
      $data['sisa_muatan']['es_tabung_besar'] -= $v['es_tabung_besar'];
      $data['sisa_muatan']['es_tabung_kecil'] -= $v['es_tabung_kecil'];
      $data['sisa_muatan']['es_tabung_kecil'] -= $v['bonus_es_tabung_kecil'];
      $data['sisa_muatan']['es_serut'] -= $v['es_serut'];
    }

    $data['pelanggan'] = $pelanggan->getDataByIdJalurPengiriman($data['surat_jalan']['id_jalur_pengiriman'], $filter, $orderKey, $orderValue);

    $data['filter'] = $filter;
    $data['order'] = \App\Core\Utilities::getOrderLinks(['nama', 'alamat']);
    
    \App\Core\Sidebar::setActiveIcon('distribusi')::setActiveLink('distribusi');
    $this->show('distribusi/daftar', $data);
    
  }

  public function form($id_pelanggan = 0) {
    
    /* Sopir only */
    if ($_SESSION['role'] != 'sopir') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $suratJalan = $this->model('SuratJalan');
    $pelanggan = $this->model('Pelanggan');
    $penjualan = $this->model('Penjualan');

    $data = array();
    $data['pelanggan'] = false;
    if ($id_pelanggan > 0) {
      $data['pelanggan'] = $pelanggan->getDataById($id_pelanggan);
      if (!$data['pelanggan']) {
        header('HTTP/1.0 404 Not Found');
        die();
      }
    }
    $data['surat_jalan'] = $suratJalan->getTodaysForSopir($_SESSION['id_user']);
    $data['total_penjualan'] = $penjualan->getTodaysPenjualanByIdUser($id_pelanggan);
    $data['error'] = array();
    $data['default'] = array(
      'nama' => '',
      'alamat' => '',
      'no_telp' => '',
      'es_tabung_besar' => 0,
      'es_tabung_kecil' => 0,
      'es_serut' => 0,
      'total_harga' => '',
      'metode_pembayaran' => $data['pelanggan'] ? $data['pelanggan']['metode_pembayaran'] : 'cash',
      'pin' => ''
    );

    if (isset($_POST['submit'])) {
      $set = array();
      $set['nama'] = isset($_POST['nama']) ? trim($_POST['nama']) : '';
      $set['no_telp'] = isset($_POST['no_telp']) ? trim($_POST['no_telp']) : '';
      $set['alamat'] = isset($_POST['alamat']) ? trim($_POST['alamat']) : '';

      $set['es_tabung_besar'] = isset($_POST['es_tabung_besar']) ? trim($_POST['es_tabung_besar']) : '';
      $set['es_tabung_kecil'] = isset($_POST['es_tabung_kecil']) ? trim($_POST['es_tabung_kecil']) : '';
      $set['es_serut'] = isset($_POST['es_serut']) ? trim($_POST['es_serut']) : '';
      $set['bonus_es_tabung_kecil'] = isset($_POST['bonus_es_tabung_kecil']) ? trim($_POST['bonus_es_tabung_kecil']) : '';
      $set['total_harga'] = isset($_POST['total_harga']) ? \App\Core\Utilities::numbersOnly(trim($_POST['total_harga'])) : '';
      $set['metode_pembayaran'] = isset($_POST['metode_pembayaran']) ? trim($_POST['metode_pembayaran']) : '';
      $set['pin'] = isset($_POST['pin']) ? trim($_POST['pin']) : '';
      
      /* Jika pelanggan baru */
      if ($id_pelanggan < 1 && $set['nama'] == '') {
        $data['error']['nama'] = 'Nama pelanggan harus diisi';
      }

      if ($id_pelanggan < 1 && $set['no_telp'] == '') {
        $data['error']['no_telp'] = 'No telp harus diisi';
      }

      if ($id_pelanggan < 1 && $set['alamat'] == '') {
        $data['error']['alamat'] = 'Alamat harus diisi';
      }
      /**/

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

      /* Jika pelanggan lama */
      if ($id_pelanggan > 0 && $set['pin'] == '') {
        $data['error']['pin'] = 'Nomor PIN harus diisi';
      }

      if ($id_pelanggan > 0 && $set['pin'] != '' && $set['pin'] != $data['pelanggan']['pin']) {
        $data['error']['pin'] = 'Nomor PIN tidak valid';
      }

      $data['default']['nama'] = $set['nama'];
      $data['default']['no_telp'] = $set['no_telp'];
      $data['default']['alamat'] = $set['alamat'];

      $data['default']['es_tabung_besar'] = $set['es_tabung_besar'];
      $data['default']['es_tabung_kecil'] = $set['es_tabung_kecil'];
      $data['default']['es_serut'] = $set['es_serut'];
      $data['default']['total_harga'] = $set['total_harga'];
      $data['default']['metode_pembayaran'] = $set['metode_pembayaran'];
      
      if (count($data['error']) < 1) {
        $set['id_user'] = $id_pelanggan;
        $set['id_surat_jalan'] = $data['surat_jalan']['id_surat_jalan'];

        if ($id_pelanggan < 1) {
          $set['id_jalur_pengiriman'] = $data['surat_jalan']['id_jalur_pengiriman'];
          $pelanggan->addCustomer($set);
          $set['id_user'] = $pelanggan->getLastInsertId();
          $nama = $set['nama'];
        }
        else {
          $nama = $data['pelanggan']['nama'];
        }

        $dataIsAdded = $penjualan->add($set);
        
        if ($dataIsAdded) {
          $lastInsertId = $penjualan->getLastInsertId();
          \App\Core\Flasher::set('distribusi-daftar', '<p><strong>Penjualan untuk `' . $nama . '` berhasil disimpan</strong>.</p>', 'success');
          header('location:' . BASE_URL . '/distribusi/');
          die();
        }
        else {
          $data['error']['header'] = $penjualan->getErrorInfo();
        }
      }
    }

    $data['title'] = $id = 'Penjualan';
    $data['button_label'] = 'Tambahkan Data';

    \App\Core\Sidebar::setActiveIcon('distribusi')::setActiveLink('distribusi');
    $this
      ->addJS('assets/js/nota.js')
      ->show('distribusi/form', $data);
  }

  public function setoran() {
    
    /* Sopir only */
    if ($_SESSION['role'] != 'sopir') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

  }

}

?>