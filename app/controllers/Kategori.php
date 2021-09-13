<?php

namespace App\Controller;

class Kategori extends \App\Core\Controller {

  public function index() {

    $kategori = $this->model('Kategori');

    $data = array();
    $data['doc_title'] = 'Daftar Kategori';

    $page = isset($_GET['page']) ? (int)$_GET['page'] : '';
    $data['kategori'] = $kategori->getPaginatedData(\App\Core\Paging::getLimit($page));
    $data['total_data'] = \App\Core\Paging::getTotalData($kategori->getSql());
    $data['paging'] = \App\Core\Paging::getLinks($data['total_data'], $page);

    $last_page = \App\Core\Paging::getLastPage();
    if ((int) $page > (int) $last_page) {
      header('location:?page=' . $last_page);
      die();
    }

    \App\Core\Sidebar::setActiveIcon('kategori')::setActiveLink('kategori');
    $this->show('kategori/daftar', $data);
    
  }

  public function form($id = 0) {

    $kategori = $this->model('Kategori');

    $data = array();
    $data['id_kategori'] = $id;
    $data['error'] = array();
    $data['default'] = array(
      'kode' => '',
      'nama' => '',
      'keterangan' => ''
    );

    $currentData = array();
    if ($id > 0) {
      $currentData = $kategori->getDataById($id);
      if ($currentData) {
        $data['default']['kode'] = $currentData['kode'];
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
      $set['kode'] = isset($_POST['kode']) ? trim($_POST['kode']) : '';
      $set['nama'] = isset($_POST['nama']) ? trim($_POST['nama']) : '';
      $set['keterangan'] = isset($_POST['keterangan']) ? trim($_POST['keterangan']) : '';
      
      if ($set['kode'] == '') {
        $data['error']['kode'] = 'Kode harus diisi';
      }

      if ($set['nama'] == '') {
        $data['error']['nama'] = 'Nama harus diisi';
      }

      $data['default']['kode'] = $set['kode'];
      $data['default']['nama'] = $set['nama'];
      $data['default']['keterangan'] = $set['keterangan'];
      
      if (count($data['error']) < 1) {
        if ($id > 0) {
          $dataIsEdited = $kategori->edit($id, $set);
          if ($dataIsEdited) {
            \App\Core\Flasher::set('kategori-form', '<p><strong>Kategori dengan nama `' . $set['nama'] . '` berhasil diedit</strong>.</p>', 'success');
            header('location:' . BASE_URL . '/kategori/form/' . $id);
            die();  
          }
          else {
            $data['error']['header'] = $kategori->getErrorCode() == '23000' ? 'Isian `kode` telah dipakai' : $kategori->getErrorInfo();
          }
        }
        else {
          $dataIsAdded = $kategori->add($set);
          if ($dataIsAdded) {
            $lastInsertId = $kategori->getLastInsertId();
            \App\Core\Flasher::set('kategori-form', '<p><strong>Kategori baru dengan nama `' . $set['nama'] . '` berhasil disimpan</strong>. <br><a href="' . BASE_URL . '/kategori/form/' . $lastInsertId . '">Edit kategori ' . $set['nama'] . '</a> atau buat baru lagi dengan menggunakan form di bawah.</p>', 'success');
            header('location:' . BASE_URL . '/kategori/form');
            die();
          }
          else {
            $data['error']['header'] = $kategori->getErrorCode() == '23000' ? 'Isian `kode` telah dipakai' : $kategori->getErrorInfo();
          }
        }
      }
    }

    $data['title'] = $id > 0 ? 'Edit Kategori' : 'Tambah Kategori';
    $data['button_label'] = $id > 0 ? 'Edit Data' : 'Tambahkan Data';

    \App\Core\Sidebar::setActiveIcon('kategori')::setActiveLink('kategori');
    $this->show('kategori/form', $data);
  }

  public function hapus($id = 0) {

    $id = (int) $id;
    if ($id < 1) {
      header('location:' . BASE_URL . '/kategori');
      die();
    }

    $kategori = $this->model('Kategori');
    $data = $kategori->getDataById($id);
    $data['doc_title'] = 'Hapus Kategori';
    
    if (isset($_POST['hapus']) && $data['total_barang'] < 1) {
      $kategori->delete($id);
      \App\Core\Flasher::set('kategori-daftar', '<p><strong>Kategori dengan nama `' . $data['nama'] . '` berhasil dihapus</strong>', 'success');
      header('location:' . BASE_URL . '/kategori');
      die();
    }

    \App\Core\Sidebar::setActiveIcon('kategori')::setActiveLink('kategori');
    $this->show('kategori/hapus', $data);

  }

}

?>