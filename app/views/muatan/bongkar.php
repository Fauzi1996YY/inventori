<?php

$errorClass = array(
  'kembali_tabung_besar' => '',
  'kembali_tabung_kecil' => '',
  'kembali_serut' => '',
  'rusak_tabung_besar' => '',
  'rusak_tabung_kecil' => '',
  'rusak_serut' => ''
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
    <a href="<?php echo BASE_URL . '/muatan/' . $data['id_surat_jalan']; ?>" class="button secondary">Kembali ke daftar</a>
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
<?php \App\Core\Flasher::show('muatan-bongkar'); ?>

<!-- Main form -->
<form method="post" action="" class="main">
  
  <div class="fieldset">
    <div class="meta">
      <h2>Data surat jalan</h2>
    </div>
    <div class="fields">
      <p>
        <label for="">Jalur pengiriman:</label><br>
        <strong><?php echo $data['surat_jalan']['nama_jalur_pengiriman'];?></strong>
      </p>
      <p>
        <label for="">Sopir 1:</label><br>
        <strong><?php echo $data['surat_jalan']['nama_sopir_1'];?></strong>
      </p>
      <p>
        <label for="">Sopir 2:</label><br>
        <strong><?php echo $data['surat_jalan']['nama_sopir_2'];?></strong>
      </p>
    </div>
  </div>

  <div class="fieldset">
    <div class="meta">
      <h2>Barang kembali</h2>
    </div>
    <div class="fields">
      <table class="valign-middle">
        <tbody>
          <tr>
            <th width="40%">Jenis barang</th>
            <th width="20%" class="align-center">Dimuat</th>
            <th width="20%" class="align-center">Kembali</th>
            <th width="20%" class="align-center">Rusak</th>
          </tr>
          <tr>
            <td>Es tabung besar</td>
            <td class="align-center"><?php echo $default['muatan_tabung_besar'];?></td>
            <td class="align-center">
              <input type="number" name="kembali_tabung_besar" id="kembali_tabung_besar" class="<?php echo $errorClass['kembali_tabung_besar'];?>" value="<?php echo $default['kembali_tabung_besar']; ?>"><br>
              <?php echo $errorText['kembali_tabung_besar']; ?>
            </td>
            <td class="align-center">
              <input type="number" name="rusak_tabung_besar" id="rusak_tabung_besar" class="<?php echo $errorClass['rusak_tabung_besar'];?>" value="<?php echo $default['rusak_tabung_besar']; ?>"><br>
              <?php echo $errorText['rusak_tabung_besar']; ?>
            </td>
          </tr>
          <tr>
            <td>Es tabung kecil</td>
            <td class="align-center"><?php echo $default['muatan_tabung_kecil'];?></td>
            <td class="align-center">
              <input type="number" name="kembali_tabung_kecil" id="kembali_tabung_kecil" class="<?php echo $errorClass['kembali_tabung_kecil'];?>" value="<?php echo $default['kembali_tabung_kecil']; ?>"><br>
              <?php echo $errorText['kembali_tabung_kecil']; ?>
            </td>
            <td class="align-center">
              <input type="number" name="rusak_tabung_kecil" id="rusak_tabung_kecil" class="<?php echo $errorClass['rusak_tabung_kecil'];?>" value="<?php echo $default['rusak_tabung_kecil']; ?>"><br>
              <?php echo $errorText['rusak_tabung_kecil']; ?>
            </td>
          </tr>
          <tr>
            <td>Es tabung serut</td>
            <td class="align-center"><?php echo $default['muatan_serut'];?></td>
            <td class="align-center">
              <input type="number" name="kembali_serut" id="kembali_serut" class="<?php echo $errorClass['kembali_serut'];?>" value="<?php echo $default['kembali_serut']; ?>"><br>
              <?php echo $errorText['kembali_serut']; ?>
            </td>
            <td class="align-center">
              <input type="number" name="rusak_serut" id="rusak_serut" class="<?php echo $errorClass['rusak_serut'];?>" value="<?php echo $default['rusak_serut']; ?>"><br>
              <?php echo $errorText['rusak_serut']; ?>
            </td>
          </tr>
        </tbody>
      </table>
      <!-- <p>
        <label for="kembali_tabung_besar"><span class="mandatory">Tabung besar</span></label><br>
        <input type="number" name="muatan_tabung_besar" id="muatan_tabung_besar" class="one-fourth <?php echo $errorClass['muatan_tabung_besar'];?>" value="<?php echo $default['muatan_tabung_besar']; ?>"><br>
        <?php echo $errorText['muatan_tabung_besar']; ?>
      </p>
      <p>
        <label for="muatan_tabung_kecil"><span class="mandatory">Tabung kecil</span></label><br>
        <input type="number" name="muatan_tabung_kecil" id="muatan_tabung_kecil" class="one-fourth <?php echo $errorClass['muatan_tabung_kecil'];?>" value="<?php echo $default['muatan_tabung_kecil']; ?>"><br>
        <?php echo $errorText['muatan_tabung_kecil']; ?>
      </p>
      <p>
        <label for="muatan_serut"><span class="mandatory">Serut</span></label><br>
        <input type="number" name="muatan_serut" id="muatan_serut" class="one-fourth <?php echo $errorClass['muatan_serut'];?>" value="<?php echo $default['muatan_serut']; ?>"><br>
        <?php echo $errorText['muatan_serut']; ?>
      </p> -->
    </div>
  </div>

  <!-- Buttons -->
  <div class="buttons">
    <button class="button" name="submit" value="submit"><?php echo $data['button_label']; ?></button>
  </div>

</form>