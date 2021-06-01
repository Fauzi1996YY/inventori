<?php

$error = $data['error'];
$default = $data['default'];

$errorNama = isset($error['nama']) ? 'error' : '';
$errorNamaText = isset($error['nama']) ? '<span class="inline-error">' . $error['nama'] . '</span>' : '';

?>

<div class="heading">
  <h1><?php echo $data['title']; ?></h1>
  <div class="actions">
    <a href="<?php echo BASE_URL . '/jalur-pengiriman'; ?>" class="button secondary">Kembali ke daftar</a>
  </div>
</div>

<!-- Error notification -->
<?php if (count($error) > 0): ?>
  <div class="notification error">
    <span class="icon"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#exclamation"></use></svg></span>
    <p>Data tidak dapat disimpan karena terdapat kesalahan<?php echo isset($error['header']) ? '<br><strong>' . $error['header'] . '</strong>' : '';?></p>
  </div>
<?php endif; ?>

<!-- Flashed -->
<?php \App\Core\Flasher::show('jalur-pengiriman-form'); ?>

<!-- Main form -->
<form method="post" action="" class="main">
  <!-- Profil pengguna -->
  <div class="fieldset">
    <div class="fields">
      <p>
        <label for="nama"><span class="mandatory">Nama</span></label><br>
        <input name="nama" type="text" id="nama" class="<?php echo $errorNama; ?>" value="<?php echo $default['nama']; ?>">
        <?php echo $errorNamaText; ?>
      </p>
    </div>
  </div>

  <!-- Buttons -->
  <div class="buttons">
    <button class="button" name="submit" value="submit"><?php echo $data['button_label']; ?></button>
  </div>

</form>