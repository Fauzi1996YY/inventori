<?php

$error = $data['error'];
$default = $data['default'];

$errorPassword = isset($error['password']) ? 'error' : '';
$errorPasswordText = isset($error['password']) ? '<span class="inline-error">' . $error['password'] . '</span>' : '';

$errorRepassword = isset($error['repassword']) ? 'error' : '';
$errorRepasswordText = isset($error['repassword']) ? '<span class="inline-error">' . $error['repassword'] . '</span>' : '';

$errorNama = isset($error['nama']) ? 'error' : '';
$errorNamaText = isset($error['nama']) ? '<span class="inline-error">' . $error['nama'] . '</span>' : '';

$errorNoTelp = isset($error['no_telp']) ? 'error' : '';
$errorNoTelpText = isset($error['no_telp']) ? '<span class="inline-error">' . $error['no_telp'] . '</span>' : '';

$errorAlamat = isset($error['alamat']) ? 'error' : '';
$errorAlamatText = isset($error['alamat']) ? '<span class="inline-error">' . $error['alamat'] . '</span>' : '';

?>

<div class="heading">
  <h1><?php echo $data['title']; ?></h1>
</div>

<!-- Error notification -->
<?php if (count($error) > 0): ?>
  <div class="notification error">
    <span class="icon"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#exclamation"></use></svg></span>
    <p>Data tidak dapat disimpan karena terdapat kesalahan<?php echo isset($error['header']) ? '<br><strong>' . $error['header'] . '</strong>' : '';?></p>
  </div>
<?php endif; ?>

<!-- Flashed -->
<?php \App\Core\Flasher::show('akun-form'); ?>

<!-- Main form -->
<form method="post" action="" class="main">
  
  <div class="fieldset">
    <div class="meta">
      <h2>Profil anda</h2>
    </div>
    <div class="fields">
      <p>
        <label for="nama"><span class="mandatory">Nama pelanggan</span></label><br>
        <input name="nama" type="text" id="nama" class="<?php echo $errorNama; ?>" value="<?php echo $default['nama']; ?>">
        <?php echo $errorNamaText; ?>
      </p>
      <p>
        <label for="no_telp"><span class="mandatory">No. telp</span></label><br>
        <input name="no_telp" type="text" id="no_telp" class="<?php echo $errorNoTelp; ?>" value="<?php echo $default['no_telp']; ?>">
        <?php echo $errorNoTelpText; ?>
      </p>
      <p>
        <label for="alamat"><span class="mandatory">Alamat</span></label><br>
        <textarea name="alamat" id="alamat" cols="30" rows="3" class="<?php echo $errorAlamat; ?>"><?php echo $default['alamat']; ?></textarea>
        <?php echo $errorAlamatText; ?>
      </p>
    </div>
  </div>
  
  <!-- Informasi login -->
  <div class="fieldset">
    <div class="meta">
      <h2>Informasi login</h2>
      <p>Kosongkan kotak isian `Password` jika anda tidak ingin merubah password anda</p>
    </div>
    <div class="fields">
      <p>
        <label for="password"><span class="mandatory">Password</span></label><br>
        <input type="password" name="password" id="password" class="<?php echo $errorPassword?>">
        <?php echo $errorPasswordText; ?>
      </p>
      <p>
        <label for="repassword"><span class="mandatory">Ketik ulang password</span></label><br>
        <input type="password" name="repassword" id="repassword" class="<?php echo $errorRepassword; ?>">
        <?php echo $errorRepasswordText; ?>
      </p>
    </div>
  </div>

  <!-- Buttons -->
  <div class="buttons">
    <button class="button" name="submit" value="submit">Ubah akun</button>
  </div>

</form>