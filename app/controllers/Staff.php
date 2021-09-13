<?php

namespace App\Controller;

class Staff extends \App\Core\Controller {

  public function index() {

    $staff = $this->model('Staff');

    $data = array();
    $data['doc_title'] = 'Daftar Staff';

    $page = isset($_GET['page']) ? (int)$_GET['page'] : '';
    $data['staff'] = $staff->getPaginatedData(\App\Core\Paging::getLimit($page));
    $data['total_data'] = \App\Core\Paging::getTotalData($staff->getSql());
    $data['paging'] = \App\Core\Paging::getLinks($data['total_data'], $page);

    $last_page = \App\Core\Paging::getLastPage();
    if ((int) $page > (int) $last_page) {
      header('location:?page=' . $last_page);
      die();
    }

    \App\Core\Sidebar::setActiveIcon('staff')::setActiveLink('staff');
    $this->show('staff/daftar', $data);
    
  }

  public function form($id = 0) {

    $staff = $this->model('Staff');

    $data = array();
    $data['id_user'] = $id;
    $data['error'] = array();
    $data['default'] = array(
      'email' => '',
      'nama' => ''
    );

    $currentData = array();
    if ($id > 0) {
      $currentData = $staff->getDataById($id);
      if ($currentData) {
        $data['default']['email'] = $currentData['email'];
        $data['default']['nama'] = $currentData['nama'];
      }
      else {
        header('HTTP/1.0 403 Forbidden');
        die();
      }
    }

    if (isset($_POST['submit'])) {
      $set = array();
      $set['email'] = isset($_POST['email']) ? trim($_POST['email']) : '';
      $set['nama'] = isset($_POST['nama']) ? trim($_POST['nama']) : '';
      
      if ($set['email'] == '') {
        $data['error']['email'] = 'Email harus diisi';
      }

      if ($set['nama'] == '') {
        $data['error']['nama'] = 'Nama harus diisi';
      }

      $data['default']['email'] = $set['email'];
      $data['default']['nama'] = $set['nama'];
      
      if (count($data['error']) < 1) {
        if ($id > 0) {
          $dataIsEdited = $staff->edit($id, $set);
          if ($dataIsEdited) {
            \App\Core\Flasher::set('staff-form', '<p><strong>Staff dengan nama `' . $set['nama'] . '` berhasil diedit</strong>.</p>', 'success');
            header('location:' . BASE_URL . '/staff/form/' . $id);
            die();  
          }
          else {
            $data['error']['header'] = $staff->getErrorCode() == '23000' ? 'Isian `email` telah dipakai' : $staff->getErrorInfo();
          }
        }
        else {
          $dataIsAdded = $staff->add($set);
          if ($dataIsAdded) {
            $lastInsertId = $staff->getLastInsertId();
            \App\Core\Flasher::set('staff-form', '<p><strong>Staff baru dengan nama `' . $set['nama'] . '` berhasil disimpan</strong>. <br><a href="' . BASE_URL . '/staff/form/' . $lastInsertId . '">Edit staff ' . $set['nama'] . '</a> atau buat baru lagi dengan menggunakan form di bawah.</p>', 'success');
            header('location:' . BASE_URL . '/staff/form');
            die();
          }
          else {
            $data['error']['header'] = $staff->getErrorCode() == '23000' ? 'Isian `kode` telah dipakai' : $staff->getErrorInfo();
          }
        }
      }
    }

    $data['title'] = $id > 0 ? 'Edit Staff' : 'Tambah Staff';
    $data['button_label'] = $id > 0 ? 'Edit Data' : 'Tambahkan Data';

    \App\Core\Sidebar::setActiveIcon('staff')::setActiveLink('staff');
    $this->show('staff/form', $data);
  }

  public function hapus($id = 0) {

    $id = (int) $id;
    if ($id < 1) {
      header('location:' . BASE_URL . '/staff');
      die();
    }

    $staff = $this->model('Staff');
    $data = $staff->getDataById($id);
    $data['doc_title'] = 'Hapus Staff';
    
    if (isset($_POST['hapus']) && $data['total_barang'] < 1) {
      $staff->delete($id);
      \App\Core\Flasher::set('staff-daftar', '<p><strong>Staff dengan nama `' . $data['nama'] . '` berhasil dihapus</strong>', 'success');
      header('location:' . BASE_URL . '/staff');
      die();
    }

    \App\Core\Sidebar::setActiveIcon('staff')::setActiveLink('staff');
    $this->show('staff/hapus', $data);

  }

}

?>