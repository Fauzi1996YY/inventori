<?php

$errorClass = array(
  'id_surat_jalan_2' => '',
  'tabung_besar' => '',
  'tabung_kecil' => '',
  'serut' => ''
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
<?php include_once('header.php'); ?>

<!-- Error notification -->
<?php if (count($data['error']) > 0): ?>
  <div class="notification error">
    <span class="icon"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#exclamation"></use></svg></span>
    <p>Data tidak dapat disimpan karena terdapat kesalahan<?php echo isset($data['error']['header']) ? '<br><strong>' . $data['error']['header'] . '</strong>' : '';?></p>
  </div>
<?php endif; ?>

<!-- Flasher -->
<?php \App\Core\Flasher::show('distribusi-daftar'); ?>

<!-- Main form -->
<form method="post" action="" class="main">

  <div class="fieldset">
    <div class="fields">
      <p>
        <label for="id_surat_jalan_2"><span class="mandatory">Jalur pengiriman</span></label><br>
        <select name="id_surat_jalan_2" id="id_surat_jalan_2" class="<?php echo $errorClass['id_surat_jalan_2']; ?>">
          <option value="">Pilih jalur pengiriman</option>
          <?php foreach($data['surat_jalan_active'] as $k => $v): ?>
            <?php if ($data['surat_jalan']['id_surat_jalan'] != $v['id_surat_jalan']) : ?>
              <option value="<?php echo $v['id_surat_jalan']; ?>"><?php echo $v['nama_jalur_pengiriman']; ?> | <?php echo $v['nama_sopir_1']; ?> | <?php echo $v['nama_sopir_2']; ?></option>
            <?php endif; ?>
          <?php endforeach; ?>
        </select><br>
        <?php echo $errorText['id_surat_jalan_2']; ?>
      </p>
      <p>
        <label for="tabung_besar"><span class="mandatory">Tabung besar</span></label><br>
        <input type="number" name="tabung_besar" id="tabung_besar" class="one-fourth <?php echo $errorClass['tabung_besar'];?>" value="<?php echo $default['tabung_besar']; ?>"><br>
        <?php echo $errorText['tabung_besar']; ?>
      </p>
      <p>
        <label for="tabung_kecil"><span class="mandatory">Tabung kecil</span></label><br>
        <input type="number" name="tabung_kecil" id="tabung_kecil" class="one-fourth <?php echo $errorClass['tabung_kecil'];?>" value="<?php echo $default['tabung_kecil']; ?>"><br>
        <?php echo $errorText['tabung_kecil']; ?>
      </p>
      <p>
        <label for="serut"><span class="mandatory">Serut</span></label><br>
        <input type="number" name="serut" id="serut" class="one-fourth <?php echo $errorClass['serut'];?>" value="<?php echo $default['serut']; ?>"><br>
        <?php echo $errorText['serut']; ?>
      </p>
    </div>
  </div>
  
  <!-- Buttons -->
  <div class="buttons">
    <button class="button" name="pindah" value="submit">Pindahkan</button>
  </div>

</form>