<?php

namespace App\Controller;

class JurnalUmum extends \App\Core\Controller {

  public function __construct() {
    if (isset($_GET['pdf'])) {
      require_once BASE_DIR . '/app/vendor/fpdf/fpdf.php';
      require_once BASE_DIR . '/app/core/Pdf.php';
    }
  }

  public function index() {

    /* Admin only */
    if ($_SESSION['role'] != 'admin') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $jurnalUmum = $this->model('JurnalUmum');
    $rekening = $this->model('Rekening');

    $data = array();
    $data['doc_title'] = 'Daftar Jurnal Umum';

    $data['rekening'] = $rekening->getAllData();
    $data['id_rekening'] = isset($_GET['id_rekening']) ? $_GET['id_rekening'] : 0;
    $data['bulan'] = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
    $data['tahun'] = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
    $data['minmax'] = $jurnalUmum->getMinMaxYear();
    $data['previous_saldo'] = $jurnalUmum->getPreviousSaldo($data['id_rekening'], $data['tahun'] . '-' . $data['bulan'] . '-01');
    $data['jurnal_umum'] = $jurnalUmum->getDataFiltered($data['id_rekening'], $data['tahun'] . '-' . $data['bulan']);
    
    if (isset($_GET['pdf'])) {
      $this->pdfJurnalUmum($data);
      die();
    }

    \App\Core\Sidebar::setActiveIcon('jurnal-umum')::setActiveLink('jurnal-umum');
    $this->show('jurnal-umum/daftar', $data);
    
  }

  private function pdfJurnalUmum($data) {

    $jenis_rekening = 'Rekening belum dipilih';
    foreach ($data['rekening'] as $k => $v) {
      if ($v['id_rekening'] == $data['id_rekening']) {
        $jenis_rekening = $v['jenis_rekening'];
      }
    }
    
    $pdf = new \App\Core\Pdf();
    $pdf->SetFillColor(224, 224, 224);
    
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 8, 'Jurnal Umum', 0, 1, 'C', 0);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 8, $jenis_rekening . ', ' . \App\Core\Utilities::$monthNames[$data['bulan'] - 1] . ' ' . $data['tahun'], 0, 1, 'L', 0);

    $pdf->Cell(8, 8, 'No', 1, 0, 'L', 1);
    $pdf->Cell(34, 8, 'Tanggal', 1, 0, 'L', 1);
    $pdf->Cell(58, 8, 'Keterangan', 1, 0, 'L', 1);
    $pdf->Cell(30, 8, 'kredit', 1, 0, 'R', 1);
    $pdf->Cell(30, 8, 'Debit', 1, 0, 'R', 1);
    $pdf->Cell(30, 8, 'Saldo', 1, 1, 'R', 1);
    
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetWidths(array(8, 34, 58, 30, 30, 30));
    $pdf->SetAligns(array('L', 'L', 'L', 'R', 'R', 'R'));

    $i = 1;
    $saldo = $data['previous_saldo']['jumlah'];
    foreach ($data['jurnal_umum'] as $k => $v) {

      $kredit = '';
      $debit = '';
      if ($v['arus_kas'] == 'masuk') {
        $kredit = 'Rp. ' . \App\Core\Utilities::formatRupiah($v['jumlah']);
        $saldo = $saldo + $v['jumlah'];
      }
      else {
        $debit = 'Rp. ' . \App\Core\Utilities::formatRupiah($v['jumlah']);
        $saldo = $saldo - $v['jumlah'];
      }

      $pdf->Row(array(
        $i++
        , \App\Core\Utilities::formatDate($v['tanggal'])
        , $v['nama']
        , $kredit
        , $debit
        , 'Rp. ' . \App\Core\Utilities::formatRupiah($saldo)
      ));

    }

    $pdf->Output();
  }

  public function form($id = 0) {

    /* Admin only */
    if ($_SESSION['role'] != 'admin') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $jurnalUmum = $this->model('JurnalUmum');
    $rekening = $this->model('Rekening');
    $kategoriJurnal = $this->model('KategoriJurnal');
    $subKategoriJurnal = $this->model('SubKategoriJurnal');

    $data = array();

    $data['kategori_jurnal'] = $kategoriJurnal->getAllData();
    $data['sub_kategori_jurnal'] = $subKategoriJurnal->getAllData();
    $data['sub_kategori_jurnal_main_data'] = array();
    foreach ($data['sub_kategori_jurnal'] as $k => $v) {
      $data['sub_kategori_jurnal_main_data'][$v['id_kategori_jurnal']][$v['id_sub_kategori_jurnal']] = $v['nama'];
    }

    $data['default'] = array(
      'id_kategori_jurnal' => 0,
      'id_sub_kategori_jurnal' => 0,
      'jumlah' => '',
      'tanggal' => ''
    );

    $currentData = array();
    if ($id > 0) {
      $currentData = $jurnalUmum->getDataById($id);
      if ($currentData) {
        foreach ($data['sub_kategori_jurnal'] as $k => $v) {
          if ($v['id_sub_kategori_jurnal'] == $currentData['id_sub_kategori_jurnal']) {
            $data['default']['id_kategori_jurnal'] = $v['id_kategori_jurnal'];
          }
        }
        
        $data['default']['id_rekening'] = $currentData['id_rekening'];
        $data['default']['id_sub_kategori_jurnal'] = $currentData['id_sub_kategori_jurnal'];
        $data['default']['jumlah'] = $currentData['jumlah'];
        
        $tanggal = explode('-', $currentData['tanggal']);
        $data['default']['tanggal'] = strlen($tanggal[2]) > 1 ? $tanggal[2] : '0' . $tanggal[2];
        
        $data['bulan'] = $tanggal[1];
        $data['tahun'] = $tanggal[0];
        $data['id_rekening'] = $data['default']['id_rekening'];
      }
      else {
        header('HTTP/1.0 403 Forbidden');
        die();
      }
    }
    else {
      $data['id_rekening'] = isset($_GET['id_rekening']) ? $_GET['id_rekening'] : 0;
      $data['bulan'] = isset($_GET['bulan']) ? $_GET['bulan'] : 0;
      $data['tahun'] = isset($_GET['tahun']) ? $_GET['tahun'] : 0;
    }

    $data['rekening'] = $rekening->getDataById($data['id_rekening']);
    
    if (!$data['rekening']) {
      header('HTTP/1.0 404 Not Found');
      die();
    }

    $data['error'] = array();

    if (isset($_POST['submit'])) {
      $set = array(
        'id_rekening' => $data['rekening']['id_rekening']
      );
      $set['tanggal'] = isset($_POST['tanggal']) ? trim($_POST['tanggal']) : '';
      $set['id_kategori_jurnal'] = isset($_POST['id_kategori_jurnal']) ? trim($_POST['id_kategori_jurnal']) : 0;
      $set['id_sub_kategori_jurnal'] = isset($_POST['id_sub_kategori_jurnal']) ? trim($_POST['id_sub_kategori_jurnal']) : '';
      $set['jumlah'] = isset($_POST['jumlah']) ? trim($_POST['jumlah']) : '';
      
      if ($set['tanggal'] == '') {
        $data['error']['tanggal'] = 'Tanggal harus diisi';
      }

      if ($set['id_kategori_jurnal'] == '') {
        $data['error']['id_kategori_jurnal'] = 'Kategori jurnal harus diisi';
      }

      if ($set['id_sub_kategori_jurnal'] == '') {
        $data['error']['id_sub_kategori_jurnal'] = 'Sub kategori jurnal harus diisi';
      }

      if ($set['jumlah'] == '') {
        $data['error']['jumlah'] = 'Jumlah harus diisi';
      }

      $data['default']['tanggal'] = $set['tanggal'];
      $data['default']['id_kategori_jurnal'] = (int)$set['id_kategori_jurnal'] > 0 ? $set['id_kategori_jurnal'] : 0;
      $data['default']['id_sub_kategori_jurnal'] = (int)$set['id_sub_kategori_jurnal'] > 0 ? $set['id_sub_kategori_jurnal'] : 0;
      $data['default']['jumlah'] = $set['jumlah'];
      
      if (count($data['error']) < 1) {
        $set['tanggal'] = $data['tahun'] . '-' . $data['bulan'] . '-' . $set['tanggal'];
        if ($id > 0) {
          $dataIsEdited = $jurnalUmum->edit($id, $set);
          if ($dataIsEdited) {
            /* Edit saldo per month */
            $jurnalUmum->calculateSaldo($data['rekening']['id_rekening'], $set['tanggal']);

            \App\Core\Flasher::set('jurnal-umum-form', '<p><strong>Jurnal umum dengan jumlah `Rp. ' . \App\Core\Utilities::formatRupiah($set['jumlah']) . '` berhasil diedit</strong>.</p>', 'success');
            header('location:' . BASE_URL . '/jurnal-umum/form/' . $id);
            die();  
          }
          else {
            $data['error']['header'] = $rekening->getErrorInfo();
          }
        }
        else {
          $dataIsAdded = $jurnalUmum->add($set);
          if ($dataIsAdded) {
            /* Edit saldo per month */
            $jurnalUmum->calculateSaldo($data['rekening']['id_rekening'], $set['tanggal']);

            $lastInsertId = $jurnalUmum->getLastInsertId();
            \App\Core\Flasher::set('jurnal-umum-form', '<p><strong>Jurnal umum baru dengan jumlah `Rp. ' . \App\Core\Utilities::formatRupiah($set['jumlah']) . '` berhasil disimpan</strong>. <br><a href="' . BASE_URL . '/jurnal-umum/form/' . $lastInsertId . '">Edit data</a> atau buat baru lagi dengan menggunakan form di bawah.</p>', 'success');
            header('location:' . BASE_URL . '/jurnal-umum/form?id_rekening=' . $data['id_rekening'] . '&bulan=' . $data['bulan'] . '&tahun=' . $data['tahun']);
            die();
          }
          else {
            $data['error']['header'] = $rekening->getErrorInfo();
          }
        }
      }
    }

    $data['title'] = $id > 0 ? 'Edit Jurnal Umum' : 'Tambah Jurnal Umum';
    $data['button_label'] = $id > 0 ? 'Edit Data' : 'Tambahkan Data';

    \App\Core\Sidebar::setActiveIcon('jurnal-umum')::setActiveLink('jurnal-umum');
    $this
      ->addJS('assets/js/jurnal-umum.js')
      ->show('jurnal-umum/form', $data);
  }

  public function hapus($id = 0) {

    /* Admin only */
    if ($_SESSION['role'] != 'admin') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $jurnalUmum = $this->model('JurnalUmum');
    $data = $jurnalUmum->getDataById($id);

    if (!$data) {
      header('HTTP/1.0 404 Not Found');
      die();
    }

    $data['arr_tanggal'] = explode('-', $data['tanggal']);

    $data['doc_title'] = 'Hapus Jurnal Umum';
    
    if (isset($_POST['hapus'])) {
      $jurnalUmum->delete($id);

      /* Edit saldo per month */
      $jurnalUmum->calculateSaldo($data['id_rekening'], $data['tanggal']);

      \App\Core\Flasher::set('jurnal-umum-daftar', '<p><strong>Jurnal umum untuk tanggal `' . \App\Core\Utilities::formatDate($data['tanggal']) . '`, jenis rekening `' . $data['jenis_rekening'] . '`, sub kategori `' . $data['nama'] . '`, jumlah Rp. `' . \App\Core\Utilities::formatRupiah($data['jumlah']) . '` berhasil dihapus</strong>', 'success');
      header('location:' . BASE_URL . '/jurnal-umum?id_rekening=' . $data['id_rekening'] . '&bulan=' . $data['arr_tanggal'][1] . '&tahun=' . $data['arr_tanggal'][0]);
      die();
    }

    \App\Core\Sidebar::setActiveIcon('jurnal-umum')::setActiveLink('jurnal-umum');
    $this->show('jurnal-umum/hapus', $data);

  }

}

?>