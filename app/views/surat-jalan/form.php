<?php

$errorClass = array(
  'id_jalur_pengiriman' => '',
  'id_user_1' => '',
  'id_user_2' => ''
);

$errorText = array();
$default = $data['default'];

foreach ($errorClass as $k => $v) {
  $errorText[$k] = '';
  if (isset($data['error'][$k])) {
    $errorClass[$k] = 'error';
    $errorText[$k] = '<span class="inline-error">' . $data['error'][$k] . '</span>';
  }
}

?>

<div class="heading">
  <h1><?php echo $data['title']; ?></h1>
  <div class="actions">
    <a href="<?php echo BASE_URL . '/surat-jalan'; ?>" class="button secondary">Kembali ke daftar</a>
  </div>
</div>

<!-- Error notification -->
<?php if (count($data['error']) > 0): ?>
  <div class="notification error">
    <span class="icon"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#exclamation"></use></svg></span>
    <p>Data tidak dapat disimpan karena terdapat kesalahan<?php echo isset($data['error']['header']) ? '<br><strong>' . $data['error']['header'] . '</strong>' : '';?></p>
  </div>
<?php endif; ?>

<!-- Flashed -->
<?php \App\Core\Flasher::show('surat-jalan-form'); ?>

<!-- Main form -->
<form method="post" action="" class="main">
  
  <div class="fieldset">
    <div class="fields">
      <p>
        <label for="id_jalur_pengiriman"><span class="mandatory">Jalur pengiriman</span></label><br>
        <select name="id_jalur_pengiriman" id="id_jalur_pengiriman" class="<?php echo $errorClass['id_jalur_pengiriman']; ?>">
          <option value="">Pilih jalur pengiriman</option>
          <?php foreach ($data['jalur_pengiriman'] as $k => $v): ?>
            <option value="<?php echo $v['id_jalur_pengiriman']; ?>" <?php echo $v['id_jalur_pengiriman'] == $default['id_jalur_pengiriman'] ? 'selected' : '';?>><?php echo $v['nama']; ?></option>
          <?php endforeach; ?>
        </select><br>
        <?php echo $errorText['id_jalur_pengiriman']; ?>
      </p>
      <p>
        <label for="id_user_1"><span class="mandatory">Sopir 1</span></label><br>
        <select name="id_user_1" id="id_user_1" class="<?php echo $errorClass['id_user_1']; ?>">
          <option value="">Pilih sopir</option>
          <?php foreach ($data['sopir'] as $k => $v): ?>
            <option value="<?php echo $v['id_user']; ?>" <?php echo $v['id_user'] == $default['id_user_1'] ? 'selected' : '';?>><?php echo $v['nama']; ?></option>
          <?php endforeach; ?>
        </select><br>
        <?php echo $errorText['id_user_1']; ?>
      </p>
      <p>
        <label for="id_user_2">Sopir 2</label><br>
        <select name="id_user_2" id="id_user_2">
          <option value="">Pilih sopir</option>
          <?php foreach ($data['sopir'] as $k => $v): ?>
            <option value="<?php echo $v['id_user']; ?>" <?php echo $v['id_user'] == $default['id_user_2'] ? 'selected' : '';?>><?php echo $v['nama']; ?></option>
          <?php endforeach; ?>
        </select>
      </p>
    </div>
  </div>

  <!-- Buttons -->
  <div class="buttons">
    <button class="button" name="submit" value="submit"><?php echo $data['button_label']; ?></button>
  </div>

</form>