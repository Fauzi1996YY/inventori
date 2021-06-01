<?php

namespace App\Controller;

class Setoran extends \App\Core\Controller {

  private $dataSuratJalan = array();

  public function index($id_surat_jalan = 0, $form = '', $idx = 0) {

    /* Admin only */
    if ($_SESSION['role'] != 'admin') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $suratJalan = $this->model('SuratJalan');
    $operasional = $this->model('Operasional');
    $kasbon = $this->model('Kasbon');
    $penjualan = $this->model('Penjualan');

    $data = array();
    $data['doc_title'] = 'Setoran';
    $data['surat_jalan'] = $suratJalan->getDataById($id_surat_jalan);
    $data['error'] = array();

    /* No surat jalan */
    if (!$data['surat_jalan']) {
      header('HTTP/1.0 404 Not Found');
      die();
    }

    $this->dataSuratJalan = $data['surat_jalan'];

    if ($form != '') {
      switch ($form) {
        case 'operasional':
          $this->operasional($idx);
          return;
          break;
        case 'hapus-operasional':
          $this->hapusOperasional($idx);
          return;
          break;
        case 'kasbon':
          $this->kasbon($idx);
          return;
          break;
        case 'hapus-kasbon':
          $this->hapusKasbon($idx);
          return;
          break;
        default:
          /* No surat jalan */
          header('HTTP/1.0 404 Not Found');
          die();
          break;
      }
    }
    
    if (isset($_POST['submit'])) {
      
      $jumlah_cash = isset($_POST['jumlah_cash']) ? \App\Core\Utilities::numbersOnly(trim($_POST['jumlah_cash'])) : 0;
      $dataIsEdited = $suratJalan->setor($id_surat_jalan, $jumlah_cash);
      if ($dataIsEdited) {
        \App\Core\Flasher::set('setoran', '<p><strong>Setoran berhasil diedit</strong>.</p>', 'success');
        header('location:' . BASE_URL . '/setoran/' . $id_surat_jalan);
        die();  
      }
    }

    $data['operasional'] = $operasional->getAllData($id_surat_jalan);
    $data['kasbon'] = $kasbon->getAllData($id_surat_jalan);
    $data['pembelian'] = $penjualan->getDataPelangganByIdSuratJalan($data['surat_jalan']['id_surat_jalan']);
    $data['default'] = array(
      'jumlah_cash' => 0
    );
    $data['total_cash'] = 0;
    $data['total_invoice'] = 0;
    foreach ($data['pembelian'] as $k => $v) {
      if ($v['metode_pembayaran'] == 'cash') {
        $data['total_cash'] += $v['total_harga'];
      }
    }

    \App\Core\Sidebar::setActiveIcon('surat-jalan')::setActiveLink('surat-jalan');
    $this->show('setoran/daftar', $data);
    
  }

  public function operasional($id = 0) {

    $operasional = $this->model('Operasional');

    $data = array();
    $data['surat_jalan'] = $this->dataSuratJalan;
    $data['doc_title'] = 'Biaya Operasional';
    $data['error'] = array();
    $data['default'] = array(
      'keterangan' => '',
      'jumlah' => 0
    );

    $currentData = array();
    if ($id > 0) {
      $currentData = $operasional->getDataById($id);
      if ($currentData) {
        $data['default']['keterangan'] = $currentData['keterangan'];
        $data['default']['jumlah'] = \App\Core\Utilities::formatRupiah($currentData['jumlah']);
      }
      else {
        header('HTTP/1.0 404 Not Found');
        die();
      }
    }

    if (isset($_POST['submit'])) {
      $set = array();
      $set['keterangan'] = isset($_POST['keterangan']) ? trim($_POST['keterangan']) : '';
      $set['jumlah'] = isset($_POST['jumlah']) ? \App\Core\Utilities::numbersOnly(trim($_POST['jumlah'])) : '';
      
      if ($set['keterangan'] == '') {
        $data['error']['keterangan'] = 'Keterangan harus diisi';
      }

      if ($set['jumlah'] == '') {
        $data['error']['jumlah'] = 'Jumlah harus diisi';
      }
      else if ($set['jumlah'] < 1) {
        $data['error']['jumlah'] = 'Jumlah harus diisi';
      }

      $data['default']['keterangan'] = $set['keterangan'];
      $data['default']['jumlah'] = $set['jumlah'];
      
      if (count($data['error']) < 1) {
        if ($id > 0) {
          $dataIsEdited = $operasional->edit($id, $set);
          if ($dataIsEdited) {
            \App\Core\Flasher::set('operasional', '<p><strong>Biaya operasional berhasil diedit</strong>.</p>', 'success');
            header('location:' . BASE_URL . '/setoran/ ' . $this->dataSuratJalan['id_surat_jalan'] . ' /operasional/' . $id);
            die();  
          }
          else {
            $data['error']['header'] = $operasional->getErrorInfo();
          }
        }
        else {
          $set['id_surat_jalan'] = $this->dataSuratJalan['id_surat_jalan'];
          $dataIsAdded = $operasional->add($set);
          if ($dataIsAdded) {
            $lastInsertId = $operasional->getLastInsertId();
            \App\Core\Flasher::set('operasional', '<p><strong>Biaya operasional berhasil disimpan</strong>. <br><a href="' . BASE_URL . '/setoran/' . $this->dataSuratJalan['id_surat_jalan'] . '/operasional/' . $lastInsertId . '">Edit biaya operasional</a> atau buat baru lagi dengan menggunakan form di bawah.</p>', 'success');
            header('location:' . BASE_URL . '/setoran/' . $this->dataSuratJalan['id_surat_jalan'] . '/operasional/');
            die();
          }
          else {
            $data['error']['header'] = $operasional->getErrorInfo();
          }
        }
      }
    }

    $data['title'] = $id > 0 ? 'Edit biaya operasional' : 'Tambah biaya operasional';
    $data['button_label'] = $id > 0 ? 'Edit data' : 'Simpan data';

    \App\Core\Sidebar::setActiveIcon('surat-jalan')::setActiveLink('surat-jalan');
    $this->show('setoran/operasional', $data);
  }

  public function hapusOperasional($id = 0) {

    $suratJalan = $this->model('SuratJalan');
    $operasional = $this->model('Operasional');
    
    $data = array();
    $data['surat_jalan'] = $this->dataSuratJalan;
    $data['operasional'] = $operasional->getDataById($id);
    $data['doc_title'] = 'Hapus Biaya Operasional';
    
    if (isset($_POST['hapus'])) {
      $operasional->delete($id);
      \App\Core\Flasher::set('setoran', '<p><strong>Biaya operasional untuk <strong>`' . $data['operasional']['keterangan'] . '`</strong> berhasil dihapus</strong>', 'success');
      header('location:' . BASE_URL . '/setoran/' . $data['surat_jalan']['id_surat_jalan']);
      die();
    }

    \App\Core\Sidebar::setActiveIcon('surat-jalan')::setActiveLink('surat-jalan');
    $this->show('setoran/hapus-operasional', $data);

  }

  public function kasbon($id = 0) {

    $kasbon = $this->model('Kasbon');

    $data = array();
    $data['surat_jalan'] = $this->dataSuratJalan;
    $data['doc_title'] = 'Biaya Operasional';
    $data['error'] = array();
    $data['default'] = array(
      'id_user' => '',
      'jumlah' => 0
    );

    $currentData = array();
    if ($id > 0) {
      $currentData = $kasbon->getDataById($id);
      if ($currentData) {
        $data['default']['id_user'] = $currentData['id_user'];
        $data['default']['jumlah'] = \App\Core\Utilities::formatRupiah($currentData['jumlah']);
      }
      else {
        header('HTTP/1.0 404 Not Found');
        die();
      }
    }

    if (isset($_POST['submit'])) {
      $set = array();
      $set['id_user'] = isset($_POST['id_user']) ? trim($_POST['id_user']) : '';
      $set['jumlah'] = isset($_POST['jumlah']) ? \App\Core\Utilities::numbersOnly(trim($_POST['jumlah'])) : '';
      
      if ($set['id_user'] == '') {
        $data['error']['id_user'] = 'Sopir harus diisi';
      }

      if ($set['jumlah'] == '') {
        $data['error']['jumlah'] = 'Jumlah harus diisi';
      }
      else if ($set['jumlah'] < 1) {
        $data['error']['jumlah'] = 'Jumlah harus diisi';
      }

      $data['default']['id_user'] = $set['id_user'];
      $data['default']['jumlah'] = $set['jumlah'];
      
      if (count($data['error']) < 1) {
        if ($id > 0) {
          $dataIsEdited = $kasbon->edit($id, $set);
          if ($dataIsEdited) {
            \App\Core\Flasher::set('kasbon', '<p><strong>Kasbon berhasil diedit</strong>.</p>', 'success');
            header('location:' . BASE_URL . '/setoran/ ' . $this->dataSuratJalan['id_surat_jalan'] . ' /kasbon/' . $id);
            die();  
          }
          else {
            $data['error']['header'] = $kasbon->getErrorInfo();
          }
        }
        else {
          $set['id_surat_jalan'] = $this->dataSuratJalan['id_surat_jalan'];
          $dataIsAdded = $kasbon->add($set);
          if ($dataIsAdded) {
            $lastInsertId = $kasbon->getLastInsertId();
            \App\Core\Flasher::set('kasbon', '<p><strong>Kasbon berhasil disimpan</strong>. <br><a href="' . BASE_URL . '/setoran/' . $this->dataSuratJalan['id_surat_jalan'] . '/kasbon/' . $lastInsertId . '">Edit kasbon</a> atau buat baru lagi dengan menggunakan form di bawah.</p>', 'success');
            header('location:' . BASE_URL . '/setoran/' . $this->dataSuratJalan['id_surat_jalan'] . '/kasbon/');
            die();
          }
          else {
            $data['error']['header'] = $kasbon->getErrorInfo();
          }
        }
      }
    }

    $data['title'] = $id > 0 ? 'Edit kasbon' : 'Tambah kasbon';
    $data['button_label'] = $id > 0 ? 'Edit data' : 'Simpan data';

    \App\Core\Sidebar::setActiveIcon('surat-jalan')::setActiveLink('surat-jalan');
    $this->show('setoran/kasbon', $data);
  }

  public function hapusKasbon($id = 0) {

    $suratJalan = $this->model('SuratJalan');
    $kasbon = $this->model('Kasbon');
    
    $data = array();
    $data['surat_jalan'] = $this->dataSuratJalan;
    $data['kasbon'] = $kasbon->getDataById($id);
    $data['doc_title'] = 'Hapus Kasbon';
    
    if (isset($_POST['hapus'])) {
      $kasbon->delete($id);
      \App\Core\Flasher::set('setoran', '<p><strong>Kasbon untuk <strong>`' . $data['kasbon']['nama'] . '`</strong> berhasil dihapus</strong>', 'success');
      header('location:' . BASE_URL . '/setoran/' . $data['surat_jalan']['id_surat_jalan']);
      die();
    }

    \App\Core\Sidebar::setActiveIcon('surat-jalan')::setActiveLink('surat-jalan');
    $this->show('setoran/hapus-kasbon', $data);

  }

}

?>