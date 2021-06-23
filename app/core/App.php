<?php

namespace App\Core;

class App {

  protected $controller = '';
  protected $method = 'index';
  protected $params = [];

  public function __construct() {

    $url = $this->parseURL();
    
    /* Check if user is logged in. Redirect to login page if not. */
    if (!Login::userIsLoggedIn()) {
      $gotologin = true;
      if (isset($url[0]) && trim($url[0]) == 'login') {
        $gotologin = false;
      }
      if ($gotologin) {
        header('location:' . BASE_URL . '/login');
        die();
      }
    }
    
    /* Find the class name */
    if (isset($url[0])) {
      $className = str_replace(array('-', '_'), ' ', $url[0]);
      $className = ucwords($className);
      $className = str_replace(' ', '', $className);
      
      $target = 'app/controllers/' . $className . '.php';
      if (file_exists($target)) {
        $this->controller = $className;
        unset($url[0]);
      }
      else {
        die('Controller not found');
      }
    }
    else {
      switch ($_SESSION['role']) {
        case 'admin':
          $this->controller = 'SuratJalan';
          break;
        case 'sopir':
          $this->controller = 'Distribusi';
          break;
        case 'pelanggan':
          $this->controller = 'Pembelian';
          break;
      }
    }

    $targetController = 'app/controllers/' . $this->controller . '.php';
    if (!file_exists($targetController)) {
      die('No controller file');
    }

    require_once $targetController;
    $class = '\App\Controller\\' . $this->controller; 
    $this->controller = new $class();

    /* Find the method name */
    if (isset($url[1])) {
      $methodName = str_replace(array('-', '_'), ' ', $url[1]);
      $methodName = ucwords($methodName);
      if (isset($methodName[0])) {
        $methodName[0] = strtolower($methodName[0]);
      }
      $methodName = str_replace(' ', '', $methodName);
      
      if (method_exists($this->controller, $methodName)) {
        $this->method = $methodName;
        unset($url[1]);
      }

    }

    /* The rest of the items - if exist - will be considered as parameters */
    if (!empty($url)) {
      $this->params = array_values($url);
    }

    /* Run the controller's method with it's params */
    if (method_exists($this->controller, $this->method)) {
      call_user_func_array([$this->controller, $this->method], $this->params);
    }
    else {
      die('Controller\'s method not found');
    }
    
  }

  public function parseURL() {
  
    if (isset($_GET[REWRITE_QS])) {
      $url = rtrim($_GET[REWRITE_QS], '/');
      $url = filter_var($url, FILTER_SANITIZE_URL);
      $url = explode('/', strtolower($url));
      return $url;
    }

    return array();

  }

}

?>