<?php

namespace App\Core;

class Sidebar {

  private static $activeIcon = '';
  private static $activeLink = '';
  private static $alert = '';

  public static function getLinks() {
    
    $links = array();
    
    if ($_SESSION['role'] == 'admin') {

      $links['penjualan'] = array(
        'header' => 'Penjualan',
        'links' => array(
          'surat-jalan' => array(
            'icon' => 'truck',
            'label' => 'Surat jalan',
            'url' => BASE_URL . '/surat-jalan',
            'children' => array()
          ),
          'penjualan' => array(
            'icon' => 'shopping-basket',
            'label' => 'Penjualan',
            'url' => BASE_URL . '/penjualan',
            'children' => array()
          ),
          'daftar-setoran' => array(
            'icon' => 'hand-coin',
            'label' => 'Daftar Setoran',
            'url' => BASE_URL . '/daftar-setoran',
            'children' => array()
          )
        )
      );

      $links['master-data'] = array(
        'header' => 'Master Data',
        'links' => array(
          'pelanggan' => array(
            'icon' => 'group',
            'label' => 'Pelanggan',
            'url' => BASE_URL . '/pelanggan',
            'children' => array()
          ),
          'jalur-pengiriman' => array(
            'icon' => 'route',
            'label' => 'Jalur pengiriman',
            'url' => BASE_URL . '/jalur-pengiriman',
            'children' => array()
          ),
          'rekening' => array(
            'icon' => 'bank-card',
            'label' => 'Daftar Rekening',
            'url' => BASE_URL . '/rekening',
            'children' => array()
          ),
          'kategori-jurnal' => array(
            'icon' => 'blocks',
            'label' => 'Kategori Jurnal',
            'url' => BASE_URL . '/kategori-jurnal',
            'children' => array()
          ),
          'jurnal-umum' => array(
            'icon' => 'swap-box',
            'label' => 'Jurnal Umum',
            'url' => BASE_URL . '/jurnal-umum',
            'children' => array()
          ),
          'jenis-aset' => array(
            'icon' => 'flag',
            'label' => 'Jenis Aset',
            'url' => BASE_URL . '/jenis-aset',
            'children' => array()
          )
        )
      );

    }

    if ($_SESSION['role'] == 'sopir') {
      
      $links['app'] = array(
        'header' => 'Aplikasi',
        'links' => array(
          'distribusi' => array(
            'icon' => 'truck',
            'label' => 'Distribusi',
            'url' => BASE_URL . '/distribusi',
            'children' => array()
          ),
          'penjualan' => array(
            'icon' => 'hand-coin',
            'label' => 'Penjualan',
            'url' => BASE_URL . '/penjualan/sopir',
            'children' => array()
          )
        )
      );

    }

    if ($_SESSION['role'] == 'pelanggan') {
      
      $links['app'] = array(
        'header' => 'Aplikasi',
        'links' => array(
          'pembelian' => array(
            'icon' => 'shopping-basket',
            'label' => 'Pembelian',
            'url' => BASE_URL . '/pembelian',
            'children' => array()
          )
        )
      );

    }

    $links['akun'] = array(
      'header' => 'Akun saya',
      'links' => array(
        'akun' => array(
          'icon' => 'account',
          'label' => 'Edit akun',
          'url' => BASE_URL . '/akun',
          'children' => array()
        ),
        'logout' => array(
          'icon' => 'logout',
          'label' => 'Logout',
          'url' => BASE_URL . '/logout',
          'children' => array()
        )
      )
    );

    return $links;
  }

  public static function setActiveIcon($text) {
    self::$activeIcon = $text;
    return new self;
  }
  public static function setActiveLink($text) {
    self::$activeLink = $text;
    return new self;
  }
  public static function setAlert($text) {
    self::$activeLink = $text;
    return new self;
  }

  public static function getActiveIcon() {
    return self::$activeIcon;
  }
  public static function getActiveLink() {
    return self::$activeLink;
  }
  public static function getAlert() {
    return self::$alert;
  }

}

?>