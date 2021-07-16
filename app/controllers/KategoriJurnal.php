<?php

namespace App\Controller;

class KategoriJurnal extends \App\Core\Controller {

  public function index() {

    /* Admin only */
    if ($_SESSION['role'] != 'admin') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $kategoriJurnal = $this->model('KategoriJurnal');

    $data = array();
    $data['doc_title'] = 'Daftar Kategori Jurnal';

    $page = isset($_GET['page']) ? (int)$_GET['page'] : '';
    $data['kategori_jurnal'] = $kategoriJurnal->getPaginatedData(\App\Core\Paging::getLimit($page));
    $data['total_data'] = \App\Core\Paging::getTotalData($kategoriJurnal->getSql());
    $data['paging'] = \App\Core\Paging::getLinks($data['total_data'], $page);

    \App\Core\Sidebar::setActiveIcon('kategori-jurnal')::setActiveLink('kategori-jurnal');
    $this->show('kategori-jurnal/daftar', $data);
    
  }

  public function form($id = 0) {

    /* Admin only */
    if ($_SESSION['role'] != 'admin') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $kategoriJurnal = $this->model('KategoriJurnal');

    $data = array();
    $data['id_kategori_jurnal'] = $id;
    $data['error'] = array();
    $data['default'] = array(
      'kode' => '',
      'nama' => '',
      'keterangan' => ''
    );

    $currentData = array();
    if ($id > 0) {
      $currentData = $kategoriJurnal->getDataById($id);
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
        $data['error']['nama'] = 'Nama cabang harus diisi';
      }

      $data['default']['kode'] = $set['kode'];
      $data['default']['nama'] = $set['nama'];
      $data['default']['keterangan'] = $set['keterangan'];
      
      if (count($data['error']) < 1) {
        if ($id > 0) {
          $dataIsEdited = $kategoriJurnal->edit($id, $set);
          if ($dataIsEdited) {
            \App\Core\Flasher::set('kategori-jurnal-form', '<p><strong>Kategori jurnal dengan nama `' . $set['nama'] . '` berhasil diedit</strong>.</p>', 'success');
            header('location:' . BASE_URL . '/kategori-jurnal/form/' . $id);
            die();  
          }
          else {
            $data['error']['header'] = $kategoriJurnal->getErrorCode() == '23000' ? 'Isian `kode` atau `nama` telah dipakai' : $rekening->getErrorInfo();
          }
        }
        else {
          $dataIsAdded = $kategoriJurnal->add($set);
          if ($dataIsAdded) {
            $lastInsertId = $kategoriJurnal->getLastInsertId();
            \App\Core\Flasher::set('kategori-jurnal-form', '<p><strong>Kategori jurnal baru dengan nama `' . $set['nama'] . '` berhasil disimpan</strong>. <br><a href="' . BASE_URL . '/kategori-jurnal/form/' . $lastInsertId . '">Edit kategori jurnal ' . $set['nama'] . '</a> atau buat baru lagi dengan menggunakan form di bawah.</p>', 'success');
            header('location:' . BASE_URL . '/kategori-jurnal/form');
            die();
          }
          else {
            $data['error']['header'] = $kategoriJurnal->getErrorCode() == '23000' ? 'Isian `kode` atau `nama` telah dipakai' : $rekening->getErrorInfo();
          }
        }
      }
    }

    $data['title'] = $id > 0 ? 'Edit Kategori Jurnal' : 'Tambah Kategori Jurnal';
    $data['button_label'] = $id > 0 ? 'Edit Data' : 'Tambahkan Data';

    \App\Core\Sidebar::setActiveIcon('kategori-jurnal')::setActiveLink('kategori-jurnal');
    $this->show('kategori-jurnal/form', $data);
  }

  public function hapus($id = 0) {

    /* Admin only */
    if ($_SESSION['role'] != 'admin') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $id = (int) $id;
    if ($id < 1) {
      header('location:' . BASE_URL . '/kategori-jurnal');
      die();
    }

    $kategoriJurnal = $this->model('KategoriJurnal');
    $data = $kategoriJurnal->getDataById($id);
    $data['doc_title'] = 'Hapus Kategori Jurnal';
    
    if (isset($_POST['hapus']) && $data['total_sub_kategori_jurnal'] < 1) {
      $kategoriJurnal->delete($id);
      \App\Core\Flasher::set('kategori-jurnal-daftar', '<p><strong>Kategori jurnal dengan nama `' . $data['nama'] . '` berhasil dihapus</strong>', 'success');
      header('location:' . BASE_URL . '/kategori-jurnal');
      die();
    }

    \App\Core\Sidebar::setActiveIcon('kategori-jurnal')::setActiveLink('kategori-jurnal');
    $this->show('kategori-jurnal/hapus', $data);

  }

}

?>