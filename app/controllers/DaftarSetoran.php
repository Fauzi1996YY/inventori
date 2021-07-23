<?php

namespace App\Controller;

class DaftarSetoran extends \App\Core\Controller {

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
    
    $suratJalan = $this->model('SuratJalan');
    $penjualan = $this->model('Penjualan');
    $data = array(
      'doc_title' => 'Daftar Setoran'
    );
    
    $data['tanggal'] = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('d', strtotime('-1 days'));
    $data['bulan'] = isset($_GET['bulan']) ? $_GET['bulan'] : date('m', strtotime('-1 days'));
    $data['tahun'] = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y', strtotime('-1 days'));
    
    $data['setoran'] = $suratJalan->getSetoranPerDate(date($data['tahun'] . '-' . $data['bulan'] . '-' . $data['tanggal']));
    $data['minmax'] = $penjualan->getMinMaxYear();

    if (isset($_GET['pdf'])) {
      $this->pdfDaftarSetoran($data);
      die();
    }

    \App\Core\Sidebar::setActiveIcon('daftar-setoran')::setActiveLink('daftar-setoran');
    $this->show('daftar-setoran/daftar', $data);
    
  }

  private function pdfDaftarSetoran($data) {
    $pdf = new \App\Core\Pdf();
    $pdf->SetFillColor(224, 224, 224);
    
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 8, 'Daftar Setoran', 0, 1, 'C', 0);

    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 8, 'Tanggal: ' . $data['tanggal'] . ' ' . \App\Core\Utilities::$monthNames[$data['bulan'] - 1] . ' ' . $data['tahun'], 0, 1, 'L', 0);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(8, 8, 'No', 1, 0, 'L', 1);
    $pdf->Cell(58, 8, 'Jalur pengiriman', 1, 0, 'L', 1);
    $pdf->Cell(31, 8, 'Penerimaan cash', 1, 0, 'R', 1);
    $pdf->Cell(31, 8, 'Biaya operasional', 1, 0, 'R', 1);
    $pdf->Cell(31, 8, 'Kasbon', 1, 0, 'R', 1);
    $pdf->Cell(31, 8, 'Jumlah', 1, 1, 'R', 1);
    
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetWidths(array(8, 58, 31, 31, 31, 31));
    $pdf->SetAligns(array('L', 'L', 'R', 'R', 'R', 'R'));

    $i = 1;
    $total_penerimaan_cash = 0;
    $total_biaya_operasional = 0;
    $total_kasbon = 0;
    $total_cash = 0;
    foreach ($data['setoran'] as $k => $v) {

      $pdf->Row(array(
        $i++
        , $v['nama_jalur_pengiriman']
        , 'Rp. ' . \App\Core\Utilities::formatRupiah($v['jumlah_cash'] + $v['jumlah_biaya_operasional'] + $v['jumlah_kasbon'])
        , 'Rp. ' . \App\Core\Utilities::formatRupiah($v['jumlah_biaya_operasional'])
        , 'Rp. ' . \App\Core\Utilities::formatRupiah($v['jumlah_kasbon'])
        , 'Rp. ' . \App\Core\Utilities::formatRupiah($v['jumlah_cash'])
      ));

      $total_penerimaan_cash+= ($v['jumlah_cash'] + $v['jumlah_biaya_operasional'] + $v['jumlah_kasbon']);
      $total_biaya_operasional+= $v['jumlah_biaya_operasional'];
      $total_kasbon+= $v['jumlah_kasbon'];
      $total_cash+= $v['jumlah_cash'];
      
    }

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(66, 8, 'Total', 1, 0, 'R', 1);
    $pdf->Cell(31, 8, 'Rp. ' . \App\Core\Utilities::formatRupiah($total_penerimaan_cash), 1, 0, 'R', 1);
    $pdf->Cell(31, 8, 'Rp. ' . \App\Core\Utilities::formatRupiah($total_biaya_operasional), 1, 0, 'R', 1);
    $pdf->Cell(31, 8, 'Rp. ' . \App\Core\Utilities::formatRupiah($total_kasbon), 1, 0, 'R', 1);
    $pdf->Cell(31, 8, 'Rp. ' . \App\Core\Utilities::formatRupiah($total_cash), 1, 1, 'R', 1);

    $pdf->Output();
  }

  public function detail($id_surat_jalan) {
    /* Admin only */
    if ($_SESSION['role'] != 'admin') {
      header('HTTP/1.0 403 Forbidden');
      die();
    }

    $suratJalan = $this->model('SuratJalan');
    $operasional = $this->model('Operasional');
    $kasbon = $this->model('Kasbon');

    $data = array(
      'doc_title' => 'Detail Setoran',
      'qs_back_to_list' => isset($_GET['tanggal']) && isset($_GET['bulan']) & isset($_GET['tahun']) ? '?tanggal=' . $_GET['tanggal'] . '&bulan=' . $_GET['bulan'] . '&tahun=' . $_GET['tahun'] : ''
    );

    $data['surat_jalan'] = $suratJalan->getDataById($id_surat_jalan);
    if (!$data['surat_jalan']) {
      header('HTTP/1.0 404 Not Found');
      die();
    }

    $data['biaya_operasional'] = $operasional->getAllData($id_surat_jalan);
    $data['total_biaya_operasional'] = 0;
    foreach ($data['biaya_operasional'] as $k => $v) {
      $data['total_biaya_operasional']+= $v['jumlah'];
    }

    $data['kasbon'] = $kasbon->getAllData($id_surat_jalan);
    $data['total_kasbon'] = 0;
    foreach ($data['kasbon'] as $k => $v) {
      $data['total_kasbon']+= $v['jumlah'];
    }

    \App\Core\Sidebar::setActiveIcon('daftar-setoran')::setActiveLink('daftar-setoran');
    $this->show('daftar-setoran/detail', $data);
  }

}

?>