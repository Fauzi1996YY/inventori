<?php

$errorClass = array(
  'id_kategori' => '',
  'kode' => '',
  'nama' => '',
  'brand' => '',
  'tahun_pembuatan' => '',
  'kondisi_asset'=>'',
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
    <a href="<?php echo BASE_URL . '/barang'; ?>" class="button secondary">Kembali ke daftar</a>
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
<?php \App\Core\Flasher::show('barang-form'); ?>

<!-- Main form -->
<form method="post" action="" class="main">

  <div class="fieldset">
    <div class="fields">
      <p>
        <label for="id_kategori"><span class="mandatory">Kategori</span></label><br>
        <select name="id_kategori" id="id_kategori" class="<?php echo $errorClass['id_kategori'];?>">
          <option value="">Pilih kategori</option>
          <?php foreach ($data['kategori'] as $k => $v) : ?>
            <option value="<?php echo $v['id_kategori'];?>" <?php echo $default['id_kategori'] == $v['id_kategori'] ? 'selected' : ''; ?>><?php echo $v['kode'] . ' - ' . $v['nama']; ?></option>
          <?php endforeach; ?>
        </select><br>
        <?php echo $errorText['id_kategori']; ?>
      </p>
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
        <label for="brand">Brand</label><br>
        <input type="text" name="brand" id="brand" class="<?php echo $errorClass['brand'];?>" value="<?php echo $default['brand']; ?>"><br>
        <?php echo $errorText['brand']; ?>
      </p>
      <p>
        <label for="tahun_pembuatan">Tahun pembuatan</label><br>
        <input type="number" name="tahun_pembuatan" id="tahun_pembuatan" class="one-fourth <?php echo $errorClass['tahun_pembuatan'];?>" value="<?php echo $default['tahun_pembuatan']; ?>" min="0000" max="9999" size="4"><br>
        <?php echo $errorText['tahun_pembuatan']; ?>
      </p>
      <p>
        <label for="kondisi_asset">Kondisi Asset</label><br>
        <input type="text" name="kondisi_asset" id="kondisi_asset" class="<?php echo $errorClass['kondisi_asset'];?>" value="<?php echo $default['kondisi_asset']; ?>"><br>
        <?php echo $errorText['kondisi_asset']; ?>
      </p>
        <label for="jumlah"><span class="mandatory">Jumlah</span></label><br>
        <input type="number" name="jumlah" id="jumlah" class="one-fourth <?php echo $errorClass['jumlah'];?>" value="<?php echo $default['jumlah']; ?>"><br>
        <?php echo $errorText['jumlah']; ?>
      </p>
    </div>
  </div>
  
  <!-- Buttons -->
  <div class="buttons">
    <button class="button" name="submit" value="submit"><?php echo $data['button_label']?></button>
  </div>

</form>