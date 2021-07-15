<?php

$errorClass = array(
  'bank' => '',
  'kantor_cabang' => '',
  'nomor_rekening' => '',
  'nama_pemilik_rekening' => '',
  'jenis_rekening' => ''
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
    <a href="<?php echo BASE_URL . '/rekening'; ?>" class="button secondary">Kembali ke daftar</a>
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
<?php \App\Core\Flasher::show('rekening-form'); ?>

<!-- Main form -->
<form method="post" action="" class="main">

  <div class="fieldset">
    <div class="fields">
      <p>
        <label for="bank"><span class="mandatory">Bank</span></label><br>
        <input type="text" name="bank" id="bank" class="<?php echo $errorClass['bank'];?>" value="<?php echo $default['bank']; ?>"><br>
        <?php echo $errorText['bank']; ?>
      </p>
      <p>
        <label for="kantor_cabang"><span class="mandatory">Kantor cabang</span></label><br>
        <input type="text" name="kantor_cabang" id="kantor_cabang" class="<?php echo $errorClass['kantor_cabang'];?>" value="<?php echo $default['kantor_cabang']; ?>"><br>
        <?php echo $errorText['kantor_cabang']; ?>
      </p>
      <p>
        <label for="nomor_rekening"><span class="mandatory">Nomor rekening</span></label><br>
        <input type="text" name="nomor_rekening" id="nomor_rekening" class="<?php echo $errorClass['nomor_rekening'];?>" value="<?php echo $default['nomor_rekening']; ?>"><br>
        <?php echo $errorText['nomor_rekening']; ?>
      </p>
      <p>
        <label for="nama_pemilik_rekening"><span class="mandatory">Nama pemilik rekening</span></label><br>
        <input type="text" name="nama_pemilik_rekening" id="nama_pemilik_rekening" class="<?php echo $errorClass['nama_pemilik_rekening'];?>" value="<?php echo $default['nama_pemilik_rekening']; ?>"><br>
        <?php echo $errorText['nama_pemilik_rekening']; ?>
      </p>
      <p>
        <label for="jenis_rekening"><span class="mandatory">Jenis rekening</span></label><br>
        <input type="text" name="jenis_rekening" id="jenis_rekening" class="<?php echo $errorClass['jenis_rekening'];?>" value="<?php echo $default['jenis_rekening']; ?>"><br>
        <?php echo $errorText['jenis_rekening']; ?>
      </p>
    </div>
  </div>
  
  <!-- Buttons -->
  <div class="buttons">
    <button class="button" name="submit" value="submit"><?php echo $data['button_label']?></button>
  </div>

</form>