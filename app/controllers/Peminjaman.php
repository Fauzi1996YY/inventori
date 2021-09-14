<?php

namespace App\Controller;

class Peminjaman extends \App\Core\Controller {

  public function index() {

    $peminjaman = $this->model('Peminjaman');

    $data = array();
    $data['doc_title'] = 'Daftar Peminjaman';

    $page = isset($_GET['page']) ? (int)$_GET['page'] : '';
    $data['peminjaman'] = $peminjaman->getPaginatedData(\App\Core\Paging::getLimit($page));
    $data['total_data'] = \App\Core\Paging::getTotalData($peminjaman->getSql());
    $data['paging'] = \App\Core\Paging::getLinks($data['total_data'], $page);

    $last_page = \App\Core\Paging::getLastPage();
    if ((int) $page > (int) $last_page) {
      header('location:?page=' . $last_page);
      die();
    }

    \App\Core\Sidebar::setActiveIcon('peminjaman')::setActiveLink('peminjaman');
    $this->show('peminjaman/daftar', $data);
    
  }

  public function form($id = 0) {

    $peminjaman = $this->model('Peminjaman');
    $barang = $this->model('Barang');
    $anggota = $this->model('Anggota');

    $data = array();
    $data['barang'] = $barang->getAllData();
    $data['anggota'] = $anggota->getAllData();
    $data['id_peminjaman'] = $id;
    $data['error'] = array();
    $data['default'] = array(
      'id_barang' => '',
      'id_anggota' => '',
      'jumlah' => '',
      'tgl_peminjaman' => '',
      'tgl_pengembalian' => ''
    );

    $currentData = array();
    if ($id > 0) {
      $currentData = $peminjaman->getDataById($id);
      if ($currentData) {
        $data['default']['id_barang'] = $currentData['id_barang'];
        $data['default']['id_anggota'] = $currentData['id_anggota'];
        $data['default']['jumlah'] = $currentData['jumlah'];
        $data['default']['tgl_peminjaman'] = $currentData['tgl_peminjaman'];
        $data['default']['tgl_pengembalian'] = $currentData['tgl_pengembalian'];
      }
      else {
        header('HTTP/1.0 403 Forbidden');
        die();
      }
    }

    if (isset($_POST['submit'])) {
      $set = array();
      $set['id_barang'] = isset($_POST['id_barang']) ? trim($_POST['id_barang']) : '';
      $set['id_anggota'] = isset($_POST['id_anggota']) ? trim($_POST['id_anggota']) : '';
      $set['jumlah'] = isset($_POST['jumlah']) ? trim($_POST['jumlah']) : '';
      $set['tgl_peminjaman'] = isset($_POST['tgl_peminjaman']) ? trim($_POST['tgl_peminjaman']) : '';
      $set['tgl_pengembalian'] = isset($_POST['tgl_pengembalian']) && trim($_POST['tgl_pengembalian']) != '' ? trim($_POST['tgl_pengembalian']) : null;
      
      if ($set['id_barang'] == '') {
        $data['error']['id_barang'] = 'Barang harus diisi';
      }

      if ($set['id_anggota'] == '') {
        $data['error']['id_anggota'] = 'Anggota harus diisi';
      }

      if ($set['jumlah'] == '') {
        $data['error']['jumlah'] = 'Jumlah harus lebih dari 0';
      }

      if ($set['tgl_peminjaman'] == '') {
        $data['error']['tgl_peminjaman'] = 'Tanggal peminjaman harus diisi';
      }

      $data['default']['id_barang'] = $set['id_barang'];
      $data['default']['id_anggota'] = $set['id_anggota'];
      $data['default']['jumlah'] = $set['jumlah'];
      $data['default']['tgl_peminjaman'] = $set['tgl_peminjaman'];
      $data['default']['tgl_pengembalian'] = $set['tgl_pengembalian'];
      
      if (count($data['error']) < 1) {
        if ($id > 0) {
          $dataIsEdited = $peminjaman->edit($id, $set);
          if ($dataIsEdited) {
            \App\Core\Flasher::set('peminjaman-form', '<p><strong>Peminjaman berhasil diedit</strong>.</p>', 'success');
            header('location:' . BASE_URL . '/peminjaman/form/' . $id);
            die();  
          }
          else {
            $data['error']['header'] = $peminjaman->getErrorInfo();
          }
        }
        else {
          $dataIsAdded = $peminjaman->add($set);
          if ($dataIsAdded) {
            $lastInsertId = $peminjaman->getLastInsertId();
            \App\Core\Flasher::set('peminjaman-form', '<p><strong>Peminjaman baru berhasil disimpan</strong>. <br><a href="' . BASE_URL . '/peminjaman/form/' . $lastInsertId . '">Edit peminjaman ' . $set['nama'] . '</a> atau buat baru lagi dengan menggunakan form di bawah.</p>', 'success');
            header('location:' . BASE_URL . '/peminjaman/form');
            die();
          }
          else {
            $data['error']['header'] = $peminjaman->getErrorInfo();
          }
        }
      }
    }

    $data['title'] = $id > 0 ? 'Edit Peminjaman' : 'Tambah Peminjaman';
    $data['button_label'] = $id > 0 ? 'Edit Data' : 'Tambahkan Data';

    \App\Core\Sidebar::setActiveIcon('peminjaman')::setActiveLink('peminjaman');
    $this->show('peminjaman/form', $data);
  }

  public function hapus($id = 0) {

    $id = (int) $id;
    if ($id < 1) {
      header('location:' . BASE_URL . '/peminjaman');
      die();
    }

    $peminjaman = $this->model('Peminjaman');
    $data = $peminjaman->getDataById($id);
    $data['doc_title'] = 'Hapus Peminjaman';
    
    if (isset($_POST['hapus']) && $data['total_barang'] < 1) {
      $peminjaman->delete($id);
      \App\Core\Flasher::set('peminjaman-daftar', '<p><strong>Peminjaman berhasil dihapus</strong>', 'success');
      header('location:' . BASE_URL . '/peminjaman');
      die();
    }

    \App\Core\Sidebar::setActiveIcon('peminjaman')::setActiveLink('peminjaman');
    $this->show('peminjaman/hapus', $data);

  }

}

?>