<?php

namespace App\Controller;

class PindahMuatan extends \App\Core\Controller {

  public function index() {

    /* Admin and Sopir only */
    if ($_SESSION['role'] != 'sopir') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }
    
    $muatan = $this->model('Muatan');
    $suratJalan = $this->model('SuratJalan');
    $penjualan = $this->model('Penjualan');

    $data = array();
    $data['error'] = array();
    $data['doc_title'] = 'Pindah Muatan';
    $data['surat_jalan'] = $suratJalan->getTodaysForSopir();
    $data['surat_jalan_active'] = $suratJalan->getTodaysAndActiveData();

    if (!$data['surat_jalan']) {
      \App\Core\Sidebar::setActiveIcon('distribusi')::setActiveLink('distribusi');
      $data['no_data_message'] = 'Belum ada surat jalan untuk hari ini. Hubungi admin untuk memproses surat jalan';
      $this->show('distribusi/no-data', $data);
      return;
    }

    /* This data is replicated in pindah muatan */
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
      $data['sisa_muatan']['es_tabung_besar'] += $v['terima_tabung_besar'];
      $data['sisa_muatan']['es_tabung_besar'] -= $v['kembali_tabung_besar'];
      $data['sisa_muatan']['es_tabung_besar'] -= $v['rusak_tabung_besar'];
      $data['sisa_muatan']['es_tabung_besar'] -= $v['pindah_tabung_besar'];

      $data['sisa_muatan']['es_tabung_kecil'] += $v['muatan_tabung_kecil'];
      $data['sisa_muatan']['es_tabung_kecil'] += $v['terima_tabung_kecil'];
      $data['sisa_muatan']['es_tabung_kecil'] -= $v['kembali_tabung_kecil'];
      $data['sisa_muatan']['es_tabung_kecil'] -= $v['rusak_tabung_kecil'];
      $data['sisa_muatan']['es_tabung_kecil'] -= $v['pindah_tabung_kecil'];

      $data['sisa_muatan']['es_serut'] += $v['muatan_serut'];
      $data['sisa_muatan']['es_serut'] += $v['terima_serut'];
      $data['sisa_muatan']['es_serut'] -= $v['kembali_serut'];
      $data['sisa_muatan']['es_serut'] -= $v['rusak_serut'];
      $data['sisa_muatan']['es_serut'] -= $v['pindah_serut'];

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

    $data['default'] = array(
      'id_surat_jalan' => 0,
      'tabung_besar' => 0,
      'tabung_kecil' => 0,
      'serut' => 0
    );

    if (isset($_POST['pindah'])) {
      $set = array();
      $set['id_surat_jalan_1'] = $data['surat_jalan']['id_surat_jalan'];
      $set['id_surat_jalan_2'] = isset($_POST['id_surat_jalan_2']) ? trim($_POST['id_surat_jalan_2']) : 0;
      $set['tabung_besar'] = isset($_POST['tabung_besar']) ? trim($_POST['tabung_besar']) : 0;
      $set['tabung_kecil'] = isset($_POST['tabung_kecil']) ? trim($_POST['tabung_kecil']) : 0;
      $set['serut'] = isset($_POST['serut']) ? trim($_POST['serut']) : 0;

      $data['default'] = array(
        'id_surat_jalan' => $set['id_surat_jalan_2'],
        'tabung_besar' => $set['tabung_besar'],
        'tabung_kecil' => $set['tabung_kecil'],
        'serut' => $set['serut']
      );

      if ($set['id_surat_jalan_2'] < 1) {
        $data['error']['id_surat_jalan_2'] = 'Jalur pengiriman harus diisi';
      }

      if ($set['tabung_besar'] < 1 && $set['tabung_kecil'] < 1 && $set['serut'] < 1) {
        $data['error']['header'] = 'Salah satu muatan harus lebih dari nol';
      }

      if (count($data['error']) < 1) {
        $dataIsAdded = $muatan->pindah($set);
        if ($dataIsAdded) {
          \App\Core\Flasher::set('pindah-muatan', '<p><strong>Notifikasi pindah muatan telah dikirim</strong>.<br>Sedang menunggu sopir untuk memvalidasi pindah muatan.</p>', 'success');
          header('location:' . BASE_URL . '/pindah-muatan/');
          die();
        }
        else {
          $data['error']['header'] = 'Telah terjadi kesalahan. <strong>' . $muatan->getErrorInfo() . '</strong>';
        }
      }
    }

    if (isset($_POST['batalkan'])) {
      $muatan->deletePindahan($data['surat_jalan']['id_surat_jalan']);
      header('location:' . BASE_URL . '/pindah-muatan/');
      die();
    }

    if (isset($_POST['validasi'])) {
      $muatan->execPindahan($data['surat_jalan']['id_surat_jalan']);
      header('location:' . BASE_URL . '/pindah-muatan/');
      die();
    }

    \App\Core\Sidebar::setActiveIcon('distribusi')::setActiveLink('distribusi');

    $data['pindahan'] = $muatan->pindahan($data['surat_jalan']['id_surat_jalan']);
    
    if ($data['pindahan']) {
      if ($data['pindahan']['id_surat_jalan_1'] == $data['surat_jalan']['id_surat_jalan']) {
        $this->show('pindah-muatan/submited', $data);
      }
      else {
        $this->show('pindah-muatan/notification', $data);
      }
    }
    else {
      $this->show('pindah-muatan/form', $data);
    }
    
  }

}

?>