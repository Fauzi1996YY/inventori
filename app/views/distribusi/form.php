<?php

$errorClass = array(
  'nama' => '',
  'no_telp' => '',
  'alamat' => '',
  'es_tabung_besar' => '',
  'es_tabung_kecil' => '',
  'es_serut' => '',
  'berat_total' => '',
  'total_harga' => '',
  'metode_pembayaran' => '',
  'pin' => ''
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
    <a href="<?php echo BASE_URL . '/distribusi'; ?>" class="button secondary">Kembali ke daftar</a>
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
<?php \App\Core\Flasher::show('distribusi-form'); ?>

<!-- Main form -->
<form method="post" action="" class="main nota" data-harga-satuan='<?php echo $data['pelanggan'] ? $data['pelanggan']['harga_satuan'] : ''; ?>' data-metode-pembayaran='<?php echo $data['pelanggan'] ? $data['pelanggan']['metode_pembayaran'] : ''; ?>'>

  <?php if ($data['pelanggan']): ?>

    <div class="fieldset">
      <div class="meta">
        <h2>Data pelanggan</h2>
      </div>
      <div class="fields">
        <p>
          <label for="">Nama</label><br>
          <strong><?php echo $data['pelanggan']['nama'];?></strong>
        </p>
        <p>
          <label for="">Alamat</label><br>
          <strong><?php echo $data['pelanggan']['alamat'];?></strong>
        </p>
        <p>
          <label for="">Pembelian hari ini</label><br>
          <strong><?php echo $data['total_penjualan']['total']  < 1 ? 'Pertama' : 'Ke ' . ($data['total_penjualan']['total'] + 1);?> kali</strong>
        </p>
      </div>
    </div>

  <?php else: ?>

    <div class="fieldset">
      <div class="meta">
        <h2>Data pelanggan</h2>
      </div>
      <div class="fields">
        <p>
          <label for="nama"><span class="mandatory">Nama</span></label><br>
          <input type="text" name="nama" id="nama" class="<?php echo $errorClass['nama']; ?>" value="<?php echo $default['nama']; ?>"><br>
          <?php echo $errorText['nama']; ?>
        </p>
        <p>
          <label for="no_telp"><span class="mandatory">No Telp</span></label><br>
          <input type="text" name="no_telp" id="no_telp" class="<?php echo $errorClass['no_telp']; ?>" value="<?php echo $default['no_telp']; ?>"><br>
          <?php echo $errorText['no_telp']; ?>
        </p>
        <p>
          <label for="alamat"><span class="mandatory">Alamat</span></label><br>
          <textarea name="alamat" id="alamat" cols="30" rows="3" class="<?php echo $errorClass['alamat']; ?>"><?php echo $default['alamat']; ?></textarea>
          <?php echo $errorText['alamat']; ?>
        </p>
      </div>
    </div>

  <?php endif; ?>

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
        <select name="metode_pembayaran" id="metode_pembayaran" class="<?php echo $errorClass['metode_pembayaran'];?>" <?php echo !$data['pelanggan'] ? 'disabled="disabled"' : ''; ?>>
          <option value="">Pilih metode pembayaran</option>
          <option value="cash" <?php echo $data['default']['metode_pembayaran'] == 'cash' ? 'selected' : ''; ?>>Cash</option>
          <option value="invoice" <?php echo $data['default']['metode_pembayaran'] == 'invoice' ? 'selected' : ''; ?>>Invoice</option>
        </select><br>
        <?php echo $errorText['metode_pembayaran']; ?>
        <?php if (!$data['pelanggan']) : ?>
        <input type="hidden" name="metode_pembayaran" value="cash">
        <?php endif;?>
      </p>
    </div>
  </div>

  <?php if ($data['pelanggan']) : ?>
    <div class="fieldset">
      <div class="meta">
        <h2>Validasi pembeli</h2>
      </div>
      <div class="fields">
        <p>
          <label for="pin"><span class="mandatory">Nomor PIN</span></label><br>
          <input type="number" name="pin" id="pin" class="one-fourth <?php echo $errorClass['pin']; ?>" value="<?php echo $default['pin']; ?>"><br>
          <?php echo $errorText['pin']; ?>
        </p>
      </div>
    </div>
  <?php endif; ?>
  
  <!-- Buttons -->
  <div class="buttons">
    <button class="button" name="submit" value="submit"><?php echo $data['button_label']; ?></button>
  </div>

</form>