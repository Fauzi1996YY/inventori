<?php

namespace App\Controller;

class Barang extends \App\Core\Controller {

  public function index() {

    $barang = $this->model('Barang');

    $data = array();
    $data['doc_title'] = 'Daftar Barang';

    $page = isset($_GET['page']) ? (int)$_GET['page'] : '';
    $data['barang'] = $barang->getPaginatedData(\App\Core\Paging::getLimit($page));
    $data['total_data'] = \App\Core\Paging::getTotalData($barang->getSql());
    $data['paging'] = \App\Core\Paging::getLinks($data['total_data'], $page);

    $last_page = \App\Core\Paging::getLastPage();
    if ((int) $page > (int) $last_page) {
      header('location:?page=' . $last_page);
      die();
    }

    \App\Core\Sidebar::setActiveIcon('barang')::setActiveLink('barang');
    $this->show('barang/daftar', $data);
    
  }

  public function form($id = 0) {

    $barang = $this->model('Barang');
    $kategori = $this->model('Kategori');

    $data = array();
    $data['kategori'] = $kategori->getAllData();
    $data['id_barang'] = $id;
    $data['error'] = array();
    $data['default'] = array(
      'id_kategori' => '',
      'kode' => '',
      'nama' => '',
      'brand' => '',
      'tahun_pembuatan' => '',
      'kondisi_asset'=>'',
      'jumlah' => ''
    );

    $currentData = array();
    if ($id > 0) {
      $currentData = $barang->getDataById($id);
      if ($currentData) {
        $data['default']['id_kategori'] = $currentData['id_kategori'];
        $data['default']['kode'] = $currentData['kode'];
        $data['default']['nama'] = $currentData['nama'];
        $data['default']['brand'] = $currentData['brand'];
        $data['default']['tahun_pembuatan'] = $currentData['tahun_pembuatan'];
        $data['default']['kondisi_aseet'] = $currentData['kondisi_asset'];
        $data['default']['jumlah'] = $currentData['jumlah'];
      }
      else {
        header('HTTP/1.0 403 Forbidden');
        die();
      }
    }

    if (isset($_POST['submit'])) {
      $set = array();
      $set['id_kategori'] = isset($_POST['id_kategori']) ? trim($_POST['id_kategori']) : '';
      $set['kode'] = isset($_POST['kode']) ? trim($_POST['kode']) : '';
      $set['nama'] = isset($_POST['nama']) ? trim($_POST['nama']) : '';
      $set['brand'] = isset($_POST['brand']) ? trim($_POST['brand']) : '';
      $set['tahun_pembuatan'] = isset($_POST['tahun_pembuatan']) ? trim($_POST['tahun_pembuatan']) : '';
      $set['kondisi_asset'] = isset($_POST['kondisi_asset']) ? trim($_POST['kondisi_asset']) : '';
      $set['jumlah'] = isset($_POST['tahun_pembuatan']) ? trim($_POST['jumlah']) : '';
      
      if ($set['id_kategori'] == '') {
        $data['error']['id_kategori'] = 'Kategori harus diisi';
      }

      if ($set['kode'] == '') {
        $data['error']['kode'] = 'Kode harus diisi';
      }

      if ($set['nama'] == '') {
        $data['error']['nama'] = 'Nama harus diisi';
      }

      if ($set['kondisi_asset'] == '') {
        $data['error']['kondisi_asset'] = 'Kondisi asset harus diisi';
      }

      if ($set['jumlah'] == '') {
        $data['error']['jumlah'] = 'Jumlah harus diisi';
      }

      $data['default']['id_kategori'] = $set['id_kategori'];
      $data['default']['kode'] = $set['kode'];
      $data['default']['nama'] = $set['nama'];
      $data['default']['brand'] = $set['brand'];
      $data['default']['tahun_pembuatan'] = $set['tahun_pembuatan'];
      $data['default']['kondisi_asset'] = $set['kondisi_asset'];
      $data['default']['jumlah'] = $set['jumlah'];
      
      if (count($data['error']) < 1) {
        if ($id > 0) {
          $dataIsEdited = $barang->edit($id, $set);
          if ($dataIsEdited) {
            \App\Core\Flasher::set('barang-form', '<p><strong>Barang dengan nama `' . $set['nama'] . '` berhasil diedit</strong>.</p>', 'success');
            header('location:' . BASE_URL . '/barang/form/' . $id);
            die();  
          }
          else {
            $data['error']['header'] = $barang->getErrorCode() == '23000' ? 'Isian `kode` telah dipakai' : $barang->getErrorInfo();
          }
        }
        else {
          $dataIsAdded = $barang->add($set);
          if ($dataIsAdded) {
            $lastInsertId = $barang->getLastInsertId();
            \App\Core\Flasher::set('barang-form', '<p><strong>Barang baru dengan nama `' . $set['nama'] . '` berhasil disimpan</strong>. <br><a href="' . BASE_URL . '/barang/form/' . $lastInsertId . '">Edit barang ' . $set['nama'] . '</a> atau buat baru lagi dengan menggunakan form di bawah.</p>', 'success');
            header('location:' . BASE_URL . '/barang/form');
            die();
          }
          else {
            $data['error']['header'] = $barang->getErrorCode() == '23000' ? 'Isian `kode` telah dipakai' : $barang->getErrorInfo();
          }
        }
      }
    }

    $data['title'] = $id > 0 ? 'Edit Barang' : 'Tambah Barang';
    $data['button_label'] = $id > 0 ? 'Edit Data' : 'Tambahkan Data';

    \App\Core\Sidebar::setActiveIcon('barang')::setActiveLink('barang');
    $this->show('barang/form', $data);
  }

  public function hapus($id = 0) {

    $id = (int) $id;
    if ($id < 1) {
      header('location:' . BASE_URL . '/barang');
      die();
    }

    $barang = $this->model('Barang');
    $data = $barang->getDataById($id);
    $data['doc_title'] = 'Hapus Barang';
    
    if (isset($_POST['hapus']) && $data['total_barang'] < 1) {
      $barang->delete($id);
      \App\Core\Flasher::set('barang-daftar', '<p><strong>Barang dengan nama `' . $data['nama'] . '` berhasil dihapus</strong>', 'success');
      header('location:' . BASE_URL . '/barang');
      die();
    }

    \App\Core\Sidebar::setActiveIcon('barang')::setActiveLink('barang');
    $this->show('barang/hapus', $data);

  }

}

?>