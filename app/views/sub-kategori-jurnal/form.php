<?php

$errorClass = array(
  'kode' => '',
  'nama' => '',
  'arus_kas' => '',
  'keterangan' => ''
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
    <a href="<?php echo BASE_URL . '/sub_kategori-jurnal/' . $data['id_kategori_jurnal']; ?>" class="button secondary">Kembali ke daftar</a>
  </div>
</div>

<!-- Error notification -->
<?php if (count($data['error']) > 0): ?>
  <div class="notification error">
    <span class="icon"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#exclamation"></use></svg></span>
    <p>Data tidak dapat disimpan karena terdapat kesalahan<?php echo isset($data['error']['header']) ? '<br><strong>' . $data['error']['header'] . '</strong>' : '';?></p>
  </div>
<?php endif; ?>

<!-- Flasher -->
<?php \App\Core\Flasher::show('sub-kategori-jurnal-form'); ?>

<!-- Main form -->
<form method="post" action="" class="main">

  <div class="fieldset">
    <div class="fields">
      <p>
        <label for="kode"><span class="mandatory">Kode</span></label><br>
        <input type="text" name="kode" id="kode" class="<?php echo $errorClass['kode'];?>" value="<?php echo $default['kode']; ?>"><br>
        <?php echo $errorText['kode']; ?>
      </p>
      <p>
        <label for="nama"><span class="mandatory">Nama</span></label><br>
        <input type="text" name="nama" id="nama" class="<?php echo $errorClass['nama'];?>" value="<?php echo $default['nama']; ?>"><br>
        <?php echo $errorText['nama']; ?>
      </p>
      <p>
        <label for="arus_kas"><span class="mandatory">Arus kas</span></label><br>
        <label for="masuk"><input type="radio" name="arus_kas" id="masuk" value="masuk" <?php echo $default['arus_kas'] == 'masuk' ? 'checked' : '';?>> <span class="checked-bold">Masuk</span></label><br>
        <label for="keluar"><input type="radio" name="arus_kas" id="keluar" value="keluar" <?php echo $default['arus_kas'] == 'keluar' ? 'checked' : '';?>> <span class="checked-bold">Keluar</span></label><br>
        <?php echo $errorText['arus_kas']; ?>
      </p>
      <p>
        <label for="keterangan">Keterangan</label><br>
        <textarea name="keterangan" id="keterangan" cols="30" rows="3" class="<?php echo $errorClass['keterangan'];?>"><?php echo nl2br($default['keterangan']); ?></textarea>
        <?php echo $errorText['keterangan']; ?>
      </p>
    </div>
  </div>
  
  <!-- Buttons -->
  <div class="buttons">
    <button class="button" name="submit" value="submit"><?php echo $data['button_label']?></button>
  </div>

</form>