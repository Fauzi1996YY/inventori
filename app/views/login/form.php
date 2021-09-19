<!doctype html>
<html class="no-js" lang="en-US">
<head>
  <meta charset="utf-8">
  <title><?php echo isset($data['doc_title']) ? $data['doc_title'] . ' | ' . APP_NAME : APP_NAME; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
  <form action="" method="post" class="login">
    <h1>Aplikasi Inventori</h1>
    <p>Selamat datang! Silakan gunakan form di bawah untuk masuk ke aplikasi</p>
    <?php if ($data['error'] != '') : ?>
    <div class="notification error">
      <span class="icon"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#exclamation"></use></svg></span>
      <p class="smaller-text"><?php echo $data['error']; ?></p>
    </div>
    <?php endif; ?>
    <p>
      <label for="email">Alamat email</label><br>
      <input type="email" name="email">
    </p>
    <p>
      <label for="password">Password</label><br>
      <input type="password" name="password">
    </p>
    <p>
      <input type="submit" name="login" value="Login" class="button">
    </p>
  </form>
</body>
</html>