<?php

$errorClass = array(
  'es_tabung_besar' => '',
  'es_tabung_kecil' => '',
  'es_serut' => '',
  'berat_total' => '',
  'total_harga' => '',
  'metode_pembayaran' => ''
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
  <h1>Data Penjualan</h1>
  <div class="actions">
    <a href="<?php echo BASE_URL . '/penjualan/' . $data['penjualan']['id_surat_jalan']; ?>" class="button secondary">Kembali ke daftar</a>
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
<?php \App\Core\Flasher::show('penjualan-form'); ?>

<!-- Main form -->
<form method="post" action="" class="main nota" data-harga-satuan='<?php echo $data['penjualan']['harga_satuan']; ?>' data-metode-pembayaran='<?php echo $data['penjualan']['metode_pembayaran']; ?>' data-bonus='<?php echo $data['penjualan']['bonus']; ?>'>

  <div class="fieldset">
    <div class="meta">
      <h2>Data akun</h2>
    </div>
    <div class="fields">
      <p>
        <label for="">Jalur pengiriman</label><br>
        <strong><?php echo $data['penjualan']['nama_jalur'];?></strong>
      </p>
      <p>
        <label for="">Nama sopir 1</label><br>
        <strong><?php echo $data['penjualan']['nama_sopir_1'];?></strong>
      </p>
      <p>
        <label for="">Nama sopir 2</label><br>
        <strong><?php echo $data['penjualan']['nama_sopir_2'];?></strong>
      </p>
      <p>
        <label for="nama_pembeli">Nama pembeli</label><br>
        <strong><?php echo $data['penjualan']['nama_pembeli'];?></strong>
      </p>
    </div>
  </div>

  <div class="fieldset">
    <div class="meta">
      <h2>Data barang</h2>
    </div>
    <div class="fields">
      <p>
        <label for="es_tabung_besar"><span class="mandatory">Es tabung besar</span></label><br>
        <input type="number" name="es_tabung_besar" id="es_tabung_besar" class="one-fourth <?php echo $errorClass['es_tabung_besar']; ?>" value="<?php echo $default['es_tabung_besar']; ?>"><br>
        <?php echo $errorText['es_tabung_besar']; ?>
      </p>
      <p>
        <label for="es_tabung_kecil"><span class="mandatory">Es tabung kecil</span></label><br>
        <input type="number" name="es_tabung_kecil" id="es_tabung_kecil" class="one-fourth <?php echo $errorClass['es_tabung_kecil']; ?>" value="<?php echo $default['es_tabung_kecil']; ?>"><br>
        <?php echo $errorText['es_tabung_kecil']; ?>
      </p>
      <p>
        <label for="es_serut"><span class="mandatory">Es serut</span></label><br>
        <input type="number" name="es_serut" id="es_serut" class="one-fourth <?php echo $errorClass['es_serut']; ?>" value="<?php echo $default['es_serut']; ?>"><br>
        <?php echo $errorText['es_serut']; ?>
      </p>
      <p class="bonus_field">
        <label for="">Bonus es tabung kecil</label><br>
        <input type="hidden" name="bonus_es_tabung_kecil" id="bonus_es_tabung_kecil" value="">
        <strong class="green-text">Bonus <span class="bonus_number"></span> kantong</strong>
      </p>
      <p>
        <label for="berat_total"><span class="mandatory">Berat total</span></label><br>
        <input type="number" step=".01" name="berat_total" id="berat_total" class="one-fourth <?php echo $errorClass['berat_total']; ?>" value="<?php echo $default['berat_total']; ?>"><br>
        <?php echo $errorText['berat_total']; ?>
      </p>
    </div>
  </div>

  <div class="fieldset">
    <div class="meta">
      <h2>Pembayaran</h2>
    </div>
    <div class="fields">
      <p>
        <label for="total_harga"><span class="mandatory">Total harga</span></label><br>
        <input type="number" name="total_harga" id="total_harga" class="one-fourth <?php echo $errorClass['total_harga']; ?>" value="<?php echo $default['total_harga']; ?>"><br>
        <?php echo $errorText['total_harga']; ?>
      </p>
      <p>
        <label for="metode_pembayaran"><span class="mandatory">Metode pembayaran</span></label><br>
        <select name="metode_pembayaran" id="metode_pembayaran" class="<?php echo $errorClass['metode_pembayaran'];?>" <?php echo $data['penjualan']['metode_pembayaran'] != '' ? 'disabled="disabled"' : '';?>>
          <option value="">Pilih metode pembayaran</option>
          <option value="cash" <?php echo $data['default']['metode_pembayaran'] == 'cash' ? 'selected' : ''; ?>>Cash</option>
          <option value="invoice" <?php echo $data['default']['metode_pembayaran'] == 'invoice' ? 'selected' : ''; ?>>Invoice</option>
        </select><br>
        <?php echo $errorText['metode_pembayaran']; ?>
        
        <?php if ($data['penjualan']['metode_pembayaran'] != '') : ?>
          <input type="hidden" name="metode_pembayaran" value="cash">
        <?php endif;?>
      </p>
    </div>
  </div>
  
  <!-- Buttons -->
  <div class="buttons">
    <button class="button" name="submit" value="submit">Edit penjualan</button>
  </div>

</form>