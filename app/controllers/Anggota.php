<?php

namespace App\Controller;

class Anggota extends \App\Core\Controller {

  public function index() {

    $anggota = $this->model('Anggota');

    $data = array();
    $data['doc_title'] = 'Daftar Anggota';

    $page = isset($_GET['page']) ? (int)$_GET['page'] : '';
    $data['anggota'] = $anggota->getPaginatedData(\App\Core\Paging::getLimit($page));
    $data['total_data'] = \App\Core\Paging::getTotalData($anggota->getSql());
    $data['paging'] = \App\Core\Paging::getLinks($data['total_data'], $page);

    $last_page = \App\Core\Paging::getLastPage();
    if ((int) $page > (int) $last_page) {
      header('location:?page=' . $last_page);
      die();
    }

    \App\Core\Sidebar::setActiveIcon('anggota')::setActiveLink('anggota');
    $this->show('anggota/daftar', $data);
    
  }

  public function form($id = 0) {

    $anggota = $this->model('Anggota');

    $data = array();
    $data['id_anggota'] = $id;
    $data['error'] = array();
    $data['default'] = array(
      'nama' => '',
      'keterangan' => ''
    );

    $currentData = array();
    if ($id > 0) {
      $currentData = $anggota->getDataById($id);
      if ($currentData) {
        $data['default']['nama'] = $currentData['nama'];
        $data['default']['keterangan'] = $currentData['keterangan'];
      }
      else {
        header('HTTP/1.0 403 Forbidden');
        die();
      }
    }

    if (isset($_POST['submit'])) {
      $set = array();
      $set['nama'] = isset($_POST['nama']) ? trim($_POST['nama']) : '';
      $set['keterangan'] = isset($_POST['keterangan']) ? trim($_POST['keterangan']) : '';
      
      if ($set['nama'] == '') {
        $data['error']['nama'] = 'Nama harus diisi';
      }

      $data['default']['nama'] = $set['nama'];
      $data['default']['keterangan'] = $set['keterangan'];
      
      if (count($data['error']) < 1) {
        if ($id > 0) {
          $dataIsEdited = $anggota->edit($id, $set);
          if ($dataIsEdited) {
            \App\Core\Flasher::set('anggota-form', '<p><strong>Anggota dengan nama `' . $set['nama'] . '` berhasil diedit</strong>.</p>', 'success');
            header('location:' . BASE_URL . '/anggota/form/' . $id);
            die();  
          }
          else {
            $data['error']['header'] = $anggota->getErrorInfo();
          }
        }
        else {
          $dataIsAdded = $anggota->add($set);
          if ($dataIsAdded) {
            $lastInsertId = $anggota->getLastInsertId();
            \App\Core\Flasher::set('anggota-form', '<p><strong>Anggota baru dengan nama `' . $set['nama'] . '` berhasil disimpan</strong>. <br><a href="' . BASE_URL . '/anggota/form/' . $lastInsertId . '">Edit anggota ' . $set['nama'] . '</a> atau buat baru lagi dengan menggunakan form di bawah.</p>', 'success');
            header('location:' . BASE_URL . '/anggota/form');
            die();
          }
          else {
            $data['error']['header'] = $anggota->getErrorInfo();
          }
        }
      }
    }

    $data['title'] = $id > 0 ? 'Edit Anggota' : 'Tambah Anggota';
    $data['button_label'] = $id > 0 ? 'Edit Data' : 'Tambahkan Data';

    \App\Core\Sidebar::setActiveIcon('anggota')::setActiveLink('anggota');
    $this->show('anggota/form', $data);
  }

  public function hapus($id = 0) {

    $id = (int) $id;
    if ($id < 1) {
      header('location:' . BASE_URL . '/anggota');
      die();
    }

    $anggota = $this->model('Anggota');
    $data = $anggota->getDataById($id);
    $data['doc_title'] = 'Hapus Anggota';
    
    if (isset($_POST['hapus']) && $data['total_barang'] < 1) {
      $anggota->delete($id);
      \App\Core\Flasher::set('anggota-daftar', '<p><strong>Anggota dengan nama `' . $data['nama'] . '` berhasil dihapus</strong>', 'success');
      header('location:' . BASE_URL . '/anggota');
      die();
    }

    \App\Core\Sidebar::setActiveIcon('anggota')::setActiveLink('anggota');
    $this->show('anggota/hapus', $data);

  }

}

?>