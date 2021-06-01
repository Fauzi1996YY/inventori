<?php

$errorClass = array(
  'username' => '',
  'id_jalur_pengiriman' => '',
  'nama' => '',
  'no_telp' => '',
  'alamat' => '',
  'harga_satuan' => '',
  'metode_pembayaran' => '',
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
    <a href="<?php echo BASE_URL . '/pelanggan'; ?>" class="button secondary">Kembali ke daftar</a>
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
<?php \App\Core\Flasher::show('pelanggan-form'); ?>

<!-- Main form -->
<form method="post" action="" class="main">

  <div class="fieldset">
    <div class="meta">
      <h2>Data akun</h2>
    </div>
    <div class="fields">
      <p>
        <label for="username"><span class="mandatory">Username</span></label><br>
        <input name="username" type="text" id="username" class="<?php echo $errorClass['username']; ?>" value="<?php echo $default['username']; ?>">
        <?php echo $errorText['username']; ?>
      </p>
      <p>
        <label for="id_jalur_pengiriman"><span class="mandatory">Jalur pengiriman</span></label><br>
        <select name="id_jalur_pengiriman" id="id_jalur_pengiriman" class="<?php echo $errorClass['id_jalur_pengiriman']; ?>">
          <option value="">Pilih jalur pengiriman</option>
          <?php foreach($data['jalur_pengiriman'] as $k => $v): ?>
            <option value="<?php echo $v['id_jalur_pengiriman']; ?>" <?php echo $v['id_jalur_pengiriman'] == $default['id_jalur_pengiriman'] ? 'selected' : '';?>><?php echo $v['nama']; ?></option>
          <?php endforeach; ?>
        </select><br>
        <?php echo $errorText['id_jalur_pengiriman']; ?>
      </p>
    </div>
  </div>

  <div class="fieldset">
    <div class="meta">
      <h2>Data profil</h2>
    </div>
    <div class="fields">
      <p>
        <label for="nama"><span class="mandatory">Nama pelanggan</span></label><br>
        <input name="nama" type="text" id="nama" class="<?php echo $errorClass['nama']; ?>" value="<?php echo $default['nama']; ?>">
        <?php echo $errorText['nama']; ?>
      </p>
      <p>
        <label for="no_telp"><span class="mandatory">No. telp</span></label><br>
        <input name="no_telp" type="text" id="no_telp" class="<?php echo $errorClass['no_telp']; ?>" value="<?php echo $default['no_telp']; ?>">
        <?php echo $errorText['no_telp']; ?>
      </p>
      <p>
        <label for="alamat"><span class="mandatory">Alamat</span></label><br>
        <textarea name="alamat" id="alamat" cols="30" rows="3" class="<?php echo $errorClass['alamat']; ?>"><?php echo $default['alamat']; ?></textarea>
        <?php echo $errorText['alamat']; ?>
      </p>
    </div>
  </div>

  <div class="fieldset">
    <div class="meta">
      <h2>Harga dan metode pembayaran</h2>
    </div>
    <div class="fields">
      <p>
        <label for="harga_satuan"><span class="mandatory">Harga satuan</span></label><br>
        <input name="harga_satuan" type="number" id="harga_satuan" class="one-fourth <?php echo $errorClass['harga_satuan']; ?>" value="<?php echo \App\Core\Utilities::formatRupiah($default['harga_satuan']); ?>"><br>
        <?php echo $errorText['harga_satuan']; ?>
      </p>
      <p>
        <label for=""><span class="mandatory">Metode pembayaran</span></label><br>
        <label for="metode_pembayaran_cash"><input type="radio" name="metode_pembayaran" id="metode_pembayaran_cash" value="cash" <?php echo $default['metode_pembayaran'] == 'cash' ? 'checked' : ''; ?>><span class="checked-bold">Cash</span></label><br>
        <label for="metode_pembayaran_invoice"><input type="radio" name="metode_pembayaran" id="metode_pembayaran_invoice" value="invoice" <?php echo $default['metode_pembayaran'] == 'invoice' ? 'checked' : ''; ?>><span class="checked-bold">Invoice</span></label><br>
        <?php echo $errorText['metode_pembayaran']; ?>
      </p>
    </div>
  </div>

  <div class="fieldset">
    <div class="meta">
      <h2>Perujuk</h2>
    </div>
    <div class="fields">
      <p>
        <label for="id_user_perujuk_1">Perujuk 1</label><br>
        <select name="id_user_perujuk_1" id="id_user_perujuk_1">
          <option value="">Pilih perujuk</option>
          <?php foreach ($data['perujuk'] as $k => $v): ?>
            <option value="<?php echo $v['id_user'];?>" <?php echo $default['id_user_perujuk_1'] == $v['id_user'] ? 'selected' : ''; ?>><?php echo $v['nama']; ?></option>
          <?php endforeach; ?>
        </select>
      </p>
      <p>
        <label for="id_user_perujuk_2">Perujuk 2</label><br>
        <select name="id_user_perujuk_2" id="id_user_perujuk_2">
          <option value="">Pilih perujuk</option>
          <?php foreach ($data['perujuk'] as $k => $v): ?>
            <option value="<?php echo $v['id_user'];?>" <?php echo $default['id_user_perujuk_2'] == $v['id_user'] ? 'selected' : ''; ?>><?php echo $v['nama']; ?></option>
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