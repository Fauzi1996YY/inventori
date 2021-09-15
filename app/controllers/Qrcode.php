<?php

namespace App\Controller;

class Qrcode extends \App\Core\Controller {

  public function index($id = '') {

    $barang = $this->model('Barang');

    include_once('app/vendor/phpqrcode/qrlib.php');

    \QRcode::png($id);
  }

}

?>