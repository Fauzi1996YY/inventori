<!doctype html>
<html class="no-js" lang="ID">
<head>
  <meta charset="utf-8">
  <title><?php echo isset($data['doc_title']) ? $data['doc_title'] . ' | ' . APP_NAME : APP_NAME; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="identifier-URL" content="<?php echo BASE_URL;?>">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css?v=<?php echo APP_VERSION;?>">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/handheld.css?v=<?php echo APP_VERSION;?>">
</head>
<body>
  <!-- Layout header -->
  <div class="header">
    <div class="branding">
        <span class="toggle"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#menu"></use></svg></span><a href="<?php echo BASE_URL; ?>" class="logo"><span>Aplikasi Inventori</span></a>
    </div>
    <div class="extras"></div>
  </div>

  <!-- Layout sidebar -->
  <div class="sidebar">
    <div class="wrapper">
      <nav>
        <?php
        
        $sidebarLinks = \App\Core\Sidebar::getLinks();
        
        $activeIcon = \App\Core\Sidebar::getActiveIcon();
        $activeLink = \App\Core\Sidebar::getActiveLink();
        $alertArray = array();

        foreach ($sidebarLinks as $rootKey => $root) {
          if ($root['header'] != '') {
            
            /* Section label */
            echo '<span class="heading">' . $root['header'] . '</span>';
          }
          if (count($root['links']) < 1) {
            continue;
          }

          /* Parent list */
          echo '<ul>';
          foreach ($root['links'] as $parentKey => $parent) {
            
            /* Parent class attr */
            $parentClasses = array();
            if ($activeIcon == $parentKey) {
              $parentClasses[] = 'icon-active';
            }
            if ($activeLink == $parentKey) {
              $parentClasses[] = 'link-active';
            }
            if (in_array($parentKey, $alertArray)) {
              $parentClasses[] = 'alert';
            }

            echo '<li class="' . implode(' ', $parentClasses) . '">';
            echo '<a href="' . $parent['url'] . '"><svg><use xlink:href="' . BASE_URL . '/assets/images/sprite.svg#' . $parent['icon'] . '"></use></svg><span>' . $parent['label'] . '</span></a>';
            
            /* Children list */
            if (count($parent['children']) > 0) {
              echo '<ul>';
              foreach ($parent['children'] as $child) {
                
                /* Child class attr */
                $childClasses = array();
                if ($activeLink == $parentKey . '/' . $child['uid']) {
                  $childClasses[] = 'link-active';
                }

                echo '<li class="' . implode(' ', $childClasses) . '"><a href="' . BASE_URL . '/links/' . $child['uid'] . '">' . ($child['active'] == '1' ? '' : '<span class="warning"></span>') . $child['domain_name'] . '</a></li>';
              }
              echo '</ul>'; /* End of children */
            }

            echo '</li>';
          }
          echo '</ul>'; /* End of parents */
        }
        
        ?>
      </nav>
    </div>
  </div>
  <main>