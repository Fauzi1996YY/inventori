  </main>
  <script src="<?php echo BASE_URL . '/assets/js/global.js'?>"></script>
  <?php
  
  if (isset($data['js_files'])) {
    foreach ($data['js_files'] as $k) {
      echo '<script src="' . BASE_URL . '/' . $k . '"></script>';
    }
  }

  ?>
</body>
</html>