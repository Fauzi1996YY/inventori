<?php

namespace App\Core;

class Controller {

  private $headerTemplate = 'app/views/templates/header.php';
  private $footerTemplate = 'app/views/templates/footer.php';
  private $jsFiles = [];

  protected function show($view, $data = []) {

    if ($this->headerTemplate && $this->headerTemplate != null) {
      if (!file_exists($this->headerTemplate)) {
        die('Header template not found');
      }
      require_once $this->headerTemplate;
    }
    
    $targetFile = 'app/views/' . $view . '.php';
    if (!file_exists($targetFile)) {
      die('Template not found');
    }
    require_once $targetFile;

    if ($this->footerTemplate && $this->footerTemplate != null) {
      if (!file_exists($this->footerTemplate)) {
        die('Footer template not found');
      }
      $data['js_files'] = $this->jsFiles;
      require_once $this->footerTemplate;
    }
    
  }

  protected function addJS($path) {
    $this->jsFiles[] = $path;
    return $this;
  }

  protected function setHeaderTemplate($template) {
    $this->headerTemplate = $template;
    return $this;
  }

  protected function setFooterTemplate($template) {
    $this->footerTemplate = $template;
    return $this;
  }

  protected function model($model) {
    $target = 'app/models/' . $model . '.php';
    if (!file_exists($target)) {
      die('Model not found');
    }
    require_once $target;
    $class = '\App\Model\\' . $model;
    return new $class();
    
  }

}

?>