<?php

namespace App\Controller;

class Muatan extends \App\Core\Controller {

  public function index($id_surat_jalan, $form = '') {

    /* Admin only */
    if ($_SESSION['role'] != 'admin') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    if ($form != '') {
      $this->form(0, $id_surat_jalan);
      return false;
    }

    $suratJalan = $this->model('SuratJalan');
    $muatan = $this->model('Muatan');

    $data = array();
    $data['doc_title'] = 'Muatan';
    $data['surat_jalan'] = $suratJalan->getDataById($id_surat_jalan);
    $data['muatan'] = $muatan->getDataByIdSuratJalan($id_surat_jalan);
    $data['can_add'] = $data['surat_jalan']['total_muatan'] == $data['surat_jalan']['total_muatan_selesai'];
    

    \App\Core\Sidebar::setActiveIcon('surat-jalan')::setActiveLink('surat-jalan');
    $this->show('muatan/daftar', $data);
    
  }

  public function form($id_muatan = 0, $id_surat_jalan = 0) {
    /* Admin only */
    if ($_SESSION['role'] != 'admin') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $suratJalan = $this->model('SuratJalan');
    $muatan = $this->model('Muatan');
    
    $data = array();
    $data['id_surat_jalan'] = $id_surat_jalan;
    $data['error'] = array();
    $data['default'] = array(
      'muatan_tabung_besar' => 0,
      'muatan_tabung_kecil' => 0,
      'muatan_serut' => 0
    );

    $currentData = array();
    if ($id_muatan > 0) {
      $currentData = $muatan->getDataById($id_muatan);
      if ($currentData) {
        $data['id_surat_jalan'] = $currentData['id_surat_jalan'];
        $data['default']['muatan_tabung_besar'] = $currentData['muatan_tabung_besar'];
        $data['default']['muatan_tabung_kecil'] = $currentData['muatan_tabung_kecil'];
        $data['default']['muatan_serut'] = $currentData['muatan_serut'];
      }
      else {
        header('HTTP/1.0 403 Forbidden');
        die();
      }
    }

    $data['surat_jalan'] = $suratJalan->getDataById($data['id_surat_jalan']);

    if (isset($_POST['submit'])) {
      $set = array();
      $set['muatan_tabung_besar'] = isset($_POST['muatan_tabung_besar']) ? trim($_POST['muatan_tabung_besar']) : '';
      $set['muatan_tabung_kecil'] = isset($_POST['muatan_tabung_kecil']) ? trim($_POST['muatan_tabung_kecil']) : '';
      $set['muatan_serut'] = isset($_POST['muatan_serut']) ? trim($_POST['muatan_serut']) : '';
      
      if ($set['muatan_tabung_besar'] == '') {
        $data['error']['muatan_tabung_besar'] = 'Muatan tabung besar harus diisi';
      }

      if ($set['muatan_tabung_kecil'] == '') {
        $data['error']['muatan_tabung_kecil'] = 'Muatan tabung kecil harus diisi';
      }

      if ($set['muatan_serut'] == '') {
        $data['error']['muatan_serut'] = 'Muatan serut harus diisi';
      }

      $data['default']['muatan_tabung_besar'] = $set['muatan_tabung_besar'];
      $data['default']['muatan_tabung_kecil'] = $set['muatan_tabung_kecil'];
      $data['default']['muatan_serut'] = $set['muatan_serut'];
      
      if (count($data['error']) < 1) {
        $set['id_surat_jalan'] = $data['id_surat_jalan'];
        if ($id_muatan > 0) {
          $dataIsEdited = $muatan->edit($id_muatan, $set);
          if ($dataIsEdited) {
            \App\Core\Flasher::set('muatan-form', '<p><strong>Muatan berhasil diedit</strong>.</p>', 'success');
            header('location:' . BASE_URL . '/muatan/form/' . $id_muatan);
            die();  
          }
          else {
            $data['error']['header'] = $muatan->getErrorInfo();
          }
        }
        else {
          $dataIsAdded = $muatan->add($set);
          if ($dataIsAdded) {
            $lastInsertId = $muatan->getLastInsertId();
            \App\Core\Flasher::set('muatan-daftar', '<p><strong>Muatan berhasil disimpan</strong>.</p>', 'success');
            header('location:' . BASE_URL . '/muatan/' . $data['id_surat_jalan']);
            die();
          }
          else {
            $data['error']['header'] = $muatan->getErrorInfo();
          }
        }
      }
    }

    $data['title'] = $id_muatan > 0 ? 'Edit Muatan' : 'Tambah Muatan';
    $data['button_label'] = $id_muatan > 0 ? 'Edit Data' : 'Tambahkan Data';

    \App\Core\Sidebar::setActiveIcon('surat-jalan')::setActiveLink('surat-jalan');
    $this->show('muatan/form', $data);
  }

  public function bongkar($id_muatan = 0) {
    /* Admin only */
    if ($_SESSION['role'] != 'admin') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $suratJalan = $this->model('SuratJalan');
    $muatan = $this->model('Muatan');
    
    $data = array();
    $data['error'] = array();
    $data['default'] = array(
      'muatan_tabung_besar' => 0,
      'muatan_tabung_kecil' => 0,
      'muatan_serut' => 0,
      'kembali_tabung_besar' => 0,
      'kembali_tabung_kecil' => 0,
      'kembali_serut' => 0,
      'rusak_tabung_besar' => 0,
      'rusak_tabung_kecil' => 0,
      'rusak_serut' => 0
    );

    $currentData = $muatan->getDataById($id_muatan);
    if ($currentData) {
      $data['id_surat_jalan'] = $currentData['id_surat_jalan'];
      $data['default']['muatan_tabung_besar'] = $currentData['muatan_tabung_besar'];
      $data['default']['muatan_tabung_kecil'] = $currentData['muatan_tabung_kecil'];
      $data['default']['muatan_serut'] = $currentData['muatan_serut'];
      $data['default']['kembali_tabung_besar'] = $currentData['kembali_tabung_besar'];
      $data['default']['kembali_tabung_kecil'] = $currentData['kembali_tabung_kecil'];
      $data['default']['kembali_serut'] = $currentData['kembali_serut'];
      $data['default']['rusak_tabung_besar'] = $currentData['rusak_tabung_besar'];
      $data['default']['rusak_tabung_kecil'] = $currentData['rusak_tabung_kecil'];
      $data['default']['rusak_serut'] = $currentData['rusak_serut'];
    }
    else {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $data['surat_jalan'] = $suratJalan->getDataById($data['id_surat_jalan']);

    if (isset($_POST['submit'])) {
      $set = array();
      $set['kembali_tabung_besar'] = isset($_POST['kembali_tabung_besar']) ? trim($_POST['kembali_tabung_besar']) : '';
      $set['kembali_tabung_kecil'] = isset($_POST['kembali_tabung_kecil']) ? trim($_POST['kembali_tabung_kecil']) : '';
      $set['kembali_serut'] = isset($_POST['kembali_serut']) ? trim($_POST['kembali_serut']) : '';
      $set['rusak_tabung_besar'] = isset($_POST['rusak_tabung_besar']) ? trim($_POST['rusak_tabung_besar']) : '';
      $set['rusak_tabung_kecil'] = isset($_POST['rusak_tabung_kecil']) ? trim($_POST['rusak_tabung_kecil']) : '';
      $set['rusak_serut'] = isset($_POST['rusak_serut']) ? trim($_POST['rusak_serut']) : '';
      
      if ($set['kembali_tabung_besar'] == '') {
        $data['error']['kembali_tabung_besar'] = 'Tabung besar kembali harus diisi';
      }

      if ($set['kembali_tabung_kecil'] == '') {
        $data['error']['kembali_tabung_kecil'] = 'Tabung kecil kembali harus diisi';
      }

      if ($set['kembali_serut'] == '') {
        $data['error']['kembali_serut'] = 'Es serut kembali harus diisi';
      }

      if ($set['rusak_tabung_besar'] == '') {
        $data['error']['rusak_tabung_besar'] = 'Tabung besar rusak harus diisi';
      }

      if ($set['rusak_tabung_kecil'] == '') {
        $data['error']['rusak_tabung_kecil'] = 'Tabung kecil rusak harus diisi';
      }

      if ($set['rusak_serut'] == '') {
        $data['error']['rusak_serut'] = 'Es serut rusak harus diisi';
      }

      $data['default']['kembali_tabung_besar'] = $set['kembali_tabung_besar'];
      $data['default']['kembali_tabung_kecil'] = $set['kembali_tabung_kecil'];
      $data['default']['kembali_serut'] = $set['kembali_serut'];
      $data['default']['rusak_tabung_besar'] = $set['rusak_tabung_besar'];
      $data['default']['rusak_tabung_kecil'] = $set['rusak_tabung_kecil'];
      $data['default']['rusak_serut'] = $set['rusak_serut'];
      
      if (count($data['error']) < 1) {
        $dataIsEdited = $muatan->bongkar($id_muatan, $set);
        if ($dataIsEdited) {
          \App\Core\Flasher::set('muatan-bongkar', '<p><strong>Muatan berhasil dibongkar</strong>.</p>', 'success');
          header('location:' . BASE_URL . '/muatan/bongkar/' . $id_muatan);
          die();  
        }
        else {
          $data['error']['header'] = $muatan->getErrorInfo();
        }
      }
    }

    $data['title'] = 'Bongkar Muatan';
    $data['button_label'] = 'Simpan Data';

    \App\Core\Sidebar::setActiveIcon('surat-jalan')::setActiveLink('surat-jalan');
    $this->show('muatan/bongkar', $data);
  }

  public function hapus($id = 0) {

    /* Admin only */
    if ($_SESSION['role'] != 'admin') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $id = (int) $id;

    $muatan = $this->model('Muatan');
    $data = $muatan->getDataById($id);

    if (!$data) {
      header('HTTP/1.0 404 Not Found');
      die();
    }

    $data['doc_title'] = 'Hapus Muatan';
    
    if (isset($_POST['hapus']) && $data['validasi_muatan'] < 1) {
      $muatan->delete($id);
      \App\Core\Flasher::set('muatan-daftar', '<p><strong>Muatan untuk tanggal ' . \App\Core\Utilities::formatDate($data['tanggal']) . ' berhasil dihapus</strong>', 'success');
      header('location:' . BASE_URL . '/muatan/' . $data['id_surat_jalan']);
      die();
    }

    \App\Core\Sidebar::setActiveIcon('surat-jalan')::setActiveLink('surat-jalan');
    $this->show('muatan/hapus', $data);

  }

}

?>