<?php

namespace App\Controller;

class SubKategoriJurnal extends \App\Core\Controller {

  public function index($id_kategori_jurnal = 0) {

    /* Admin only */
    if ($_SESSION['role'] != 'admin') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $kategoriJurnal = $this->model('KategoriJurnal');
    $subKategoriJurnal = $this->model('SubKategoriJurnal');

    $data = array();

    $data = array();
    $data['kategori_jurnal'] = $kategoriJurnal->getDataById($id_kategori_jurnal);
    if (!$data['kategori_jurnal']) {
      header('HTTP/1.0 404 Not Found');
      die();
    }

    $data['doc_title'] = 'Daftar Sub Kategori Jurnal';

    $page = isset($_GET['page']) ? (int)$_GET['page'] : '';
    $data['sub_kategori_jurnal'] = $subKategoriJurnal->getPaginatedData(\App\Core\Paging::getLimit($page), $id_kategori_jurnal);
    $data['total_data'] = \App\Core\Paging::getTotalData($subKategoriJurnal->getSql());
    $data['paging'] = \App\Core\Paging::getLinks($data['total_data'], $page);

    $last_page = \App\Core\Paging::getLastPage();
    if ((int) $page > (int) $last_page) {
      header('location:?page=' . $last_page);
      die();
    }

    \App\Core\Sidebar::setActiveIcon('kategori-jurnal')::setActiveLink('kategori-jurnal');
    $this->show('sub-kategori-jurnal/daftar', $data);
    
  }

  public function form($id_kategori_jurnal = 0, $id_sub_kategori_jurnal = 0) {

    /* Admin only */
    if ($_SESSION['role'] != 'admin') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $kategoriJurnal = $this->model('KategoriJurnal');
    $subKategoriJurnal = $this->model('SubKategoriJurnal');

    $data = array();
    $data['kategori_jurnal'] = $kategoriJurnal->getDataById($id_kategori_jurnal);

    if (!$data['kategori_jurnal']) {
      header('HTTP/1.0 404 Not Found');
      die();
    }

    $data['id_kategori_jurnal'] = $id_kategori_jurnal;
    $data['error'] = array();
    $data['default'] = array(
      'kode' => '',
      'nama' => '',
      'arus_kas' => '',
      'keterangan' => ''
    );

    $currentData = array();
    if ($id_sub_kategori_jurnal > 0) {
      $currentData = $subKategoriJurnal->getDataById($id_sub_kategori_jurnal);
      if ($currentData) {
        $data['default']['kode'] = $currentData['kode'];
        $data['default']['nama'] = $currentData['nama'];
        $data['default']['arus_kas'] = $currentData['arus_kas'];
        $data['default']['keterangan'] = $currentData['keterangan'];
      }
      else {
        header('HTTP/1.0 404 Not Found');
        die();
      }
    }

    if (isset($_POST['submit'])) {
      $set = array();
      $set['id_kategori_jurnal'] = $id_kategori_jurnal;
      $set['kode'] = isset($_POST['kode']) ? trim($_POST['kode']) : '';
      $set['nama'] = isset($_POST['nama']) ? trim($_POST['nama']) : '';
      $set['arus_kas'] = isset($_POST['arus_kas']) ? trim($_POST['arus_kas']) : '';
      $set['keterangan'] = isset($_POST['keterangan']) ? trim($_POST['keterangan']) : '';
      
      if ($set['kode'] == '') {
        $data['error']['kode'] = 'Kode harus diisi';
      }

      if ($set['nama'] == '') {
        $data['error']['nama'] = 'Nama harus diisi';
      }

      if ($set['arus_kas'] == '') {
        $data['error']['arus_kas'] = 'Arus kas harus diisi';
      }

      $data['default']['kode'] = $set['kode'];
      $data['default']['nama'] = $set['nama'];
      $data['default']['arus_kas'] = $set['arus_kas'];
      $data['default']['keterangan'] = $set['keterangan'];
      
      if (count($data['error']) < 1) {
        if ($id_sub_kategori_jurnal > 0) {
          $dataIsEdited = $subKategoriJurnal->edit($id_sub_kategori_jurnal, $set);
          if ($dataIsEdited) {
            \App\Core\Flasher::set('sub-kategori-jurnal-form', '<p><strong>Sub kategori jurnal dengan nama `' . $set['nama'] . '` berhasil diedit</strong>.</p>', 'success');
            header('location:' . BASE_URL . '/sub-kategori-jurnal/form/' . $id_kategori_jurnal . '/' . $id_sub_kategori_jurnal);
            die();  
          }
          else {
            $data['error']['header'] = $subKategoriJurnal->getErrorCode() == '23000' ? 'Isian `kode` atau `nama` telah dipakai' : $subKategoriJurnal->getErrorInfo();
          }
        }
        else {
          $dataIsAdded = $subKategoriJurnal->add($set);
          if ($dataIsAdded) {
            $lastInsertId = $subKategoriJurnal->getLastInsertId();
            \App\Core\Flasher::set('sub-kategori-jurnal-form', '<p><strong>Sub kategori jurnal baru dengan nama `' . $set['nama'] . '` berhasil disimpan</strong>. <br><a href="' . BASE_URL . '/sub-kategori-jurnal/form/' . $id_kategori_jurnal . '/' . $lastInsertId . '">Edit sub kategori jurnal `' . $set['nama'] . '`</a> atau buat baru lagi dengan menggunakan form di bawah.</p>', 'success');
            header('location:' . BASE_URL . '/sub-kategori-jurnal/form/' . $id_kategori_jurnal);
            die();
          }
          else {
            $data['error']['header'] = $subKategoriJurnal->getErrorCode() == '23000' ? 'Isian `kode` atau `nama` telah dipakai' : $subKategoriJurnal->getErrorInfo();
          }
        }
      }
    }

    $data['title'] = $id_sub_kategori_jurnal > 0 ? 'Edit Sub Kategori Jurnal' : 'Tambah Sub Kategori Jurnal';
    $data['button_label'] = $id_sub_kategori_jurnal > 0 ? 'Edit Data' : 'Tambahkan Data';

    \App\Core\Sidebar::setActiveIcon('kategori-jurnal')::setActiveLink('kategori-jurnal');
    $this->show('sub-kategori-jurnal/form', $data);
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

    $subKategoriJurnal = $this->model('SubKategoriJurnal');
    $data = $subKategoriJurnal->getDataById($id);
    $data['doc_title'] = 'Hapus Sub Kategori Jurnal';
    
    if (isset($_POST['hapus']) && $data['total_jurnal_umum'] < 1) {
      $subKategoriJurnal->delete($id);
      \App\Core\Flasher::set('sub-kategori-jurnal-daftar', '<p><strong>Sub kategori jurnal dengan nama `' . $data['nama'] . '` berhasil dihapus</strong>', 'success');
      header('location:' . BASE_URL . '/sub-kategori-jurnal/' . $data['id_kategori_jurnal']);
      die();
    }

    \App\Core\Sidebar::setActiveIcon('kategori-jurnal')::setActiveLink('kategori-jurnal');
    $this->show('sub-kategori-jurnal/hapus', $data);

  }

}

?>