<?php

namespace App\Core;

class Sidebar {

  private static $activeIcon = '';
  private static $activeLink = '';
  private static $alert = '';

  public static function getLinks() {
    
    $links = array();
    
    $links['penjualan'] = array(
      'header' => 'Inventori',
      'links' => array(
        'barang' => array(
          'icon' => 'blocks',
          'label' => 'Barang',
          'url' => BASE_URL . '/barang',
          'children' => array()
        ),
        'peminjaman' => array(
          'icon' => 'anticlockwise',
          'label' => 'Peminjaman',
          'url' => BASE_URL . '/peminjaman',
          'children' => array()
        )
      )
    );

    $links['master-data'] = array(
      'header' => 'Master Data',
      'links' => array(
        'kategori' => array(
          'icon' => 'tag',
          'label' => 'Kategori',
          'url' => BASE_URL . '/kategori',
          'children' => array()
        ),
        'anggota' => array(
          'icon' => 'award',
          'label' => 'Data Peminjam',
          'url' => BASE_URL . '/anggota',
          'children' => array()
        )
      )
    );

    if ($_SESSION['role'] == 'admin') {
      $links['master-data']['links']['staff'] = array(
        'icon' => 'group',
        'label' => 'Staff Karyawan',
        'url' => BASE_URL . '/staff',
        'children' => array()
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