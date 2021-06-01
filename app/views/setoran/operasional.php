<?php

$errorClass = array(
  'keterangan' => '',
  'jumlah' => ''
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
    <a href="<?php echo BASE_URL . '/setoran/' . $data['surat_jalan']['id_surat_jalan']; ?>" class="button secondary">Kembali ke setoran</a>
  </div>
</div>

<!-- Flashed -->
<?php \App\Core\Flasher::show('operasional'); ?>

<!-- Error notification -->
<?php if (count($data['error']) > 0): ?>
  <div class="notification error">
    <span class="icon"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#exclamation"></use></svg></span>
    <p>Data tidak dapat disimpan karena terdapat kesalahan<?php echo isset($data['error']['header']) ? '<br><strong>' . $data['error']['header'] . '</strong>' : '';?></p>
  </div>
<?php endif; ?>

<!-- Main form -->
<form method="post" action="" class="main">

  <div class="fieldset">
    <div class="meta">
      <h2>Surat jalan</h2>
    </div>
    <div class="fields">
      <p>
        <label for="">Tanggal</label><br>
        <strong><?php echo \App\Core\Utilities::formatDate($data['surat_jalan']['tanggal']); ?></strong>
      </p>
      <p>
        <label for="">Sopir 1</label><br>
        <strong><?php echo $data['surat_jalan']['nama_sopir_1']; ?></strong>
      </p>
      <p>
        <label for="">Sopir 2</label><br>
        <strong><?php echo $data['surat_jalan']['nama_sopir_2']; ?></strong>
      </p>
    </div>
  </div>

  <div class="fieldset">
    <div class="meta">
      <h2>Biaya operasional</h2>
    </div>
    <div class="fields">
      <p>
        <label for="keterangan"><span class="mandatory">Keterangan</span></label><br>
        <textarea name="keterangan" id="keterangan" cols="30" rows="3" class="<?php echo $errorClass['keterangan'];?>"><?php echo $default['keterangan'];?></textarea><br>
        <?php echo $errorText['keterangan']; ?>
      </p>
      <p>
        <label for="jumlah"><span class="mandatory">Jumlah</span></label><br>
        <input type="number" name="jumlah" id="jumlah" class="one-fourth <?php echo $errorClass['jumlah'];?>" value="<?php echo $default['jumlah']; ?>"><br>
        <?php echo $errorText['jumlah']; ?>
      </p>
    </div>
  </div>

  <!-- Buttons -->
  <div class="buttons">
    <button class="button" name="submit" value="submit"><?php echo $data['button_label']; ?></button>
  </div>

</form>