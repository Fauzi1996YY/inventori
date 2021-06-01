<?php

namespace App\Core;

class Flasher {

  public static function set($key, $content = '', $type = '') {

    $_SESSION['flasher'][$key] = '<div class="notification ' . $type . '">
      <span class="icon"><svg><use xlink:href="' . BASE_URL . '/assets/images/sprite.svg#exclamation"></use></svg></span>
      ' . $content . '
    </div>';
  
  }

  public static function show($key) {
    
    if (isset($_SESSION['flasher']) && isset($_SESSION['flasher'][$key])) {
      echo $_SESSION['flasher'][$key];
      unset($_SESSION['flasher'][$key]);
    }

  }

}

?>