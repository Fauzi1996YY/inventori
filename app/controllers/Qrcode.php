<?php

namespace App\Controller;

class Qrcode extends \App\Core\Controller {

  public function index($id = '') {

    $barang = $this->model('Barang');
    $data = $barang->getDataById($id);
    $item = array();
    
    if (!$data) {
      die('ID barang tidak valid');
    }

    $item['url'] = BASE_URL . '/barang/form/' . $data['id_barang'];
    $item['kode'] = $data['kode'];
    $item['nama'] = $data['nama'];
    $item['tahun'] = $data['tahun_pembuatan'];
    $item['kondisi_asset'] = $data['kondisi_asset'];
    $item['brand'] = $data['brand'];
    // die(json_encode($item));
    include_once('app/vendor/phpqrcode/qrlib.php');

    \QRcode::png(json_encode($item), false, QR_ECLEVEL_L, 14);
  }

}

?>