<?php

$errorClass = array(
  'id_barang' => '',
  'id_anggota' => '',
  'jumlah' => '',
  'tgl_peminjaman' => '',
  'tgl_pengembalian' => ''
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
    <a href="<?php echo BASE_URL . '/peminjaman'; ?>" class="button secondary">Kembali ke daftar</a>
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
<?php \App\Core\Flasher::show('peminjaman-form'); ?>

<!-- Main form -->
<form method="post" action="" class="main">

  <div class="fieldset">
    <div class="fields">
      <p>
        <label for="id_barang"><span class="mandatory">Barang</span></label><br>
        <select name="id_barang" id="id_barang" class="<?php echo $errorClass['id_barang'];?>">
          <option value="">Pilih barang</option>
          <?php foreach ($data['barang'] as $k => $v) : ?>
            <option value="<?php echo $v['id_barang'];?>" <?php echo $default['id_barang'] == $v['id_barang'] ? 'selected' : ''; ?>><?php echo $v['kode'] . ' - ' . $v['nama']; ?></option>
          <?php endforeach; ?>
        </select><br>
        <?php echo $errorText['id_barang']; ?>
      </p>
      <p>
        <label for="id_anggota"><span class="mandatory">Anggota</span></label><br>
        <select name="id_anggota" id="id_anggota" class="<?php echo $errorClass['id_anggota'];?>">
          <option value="">Pilih anggota</option>
          <?php foreach ($data['anggota'] as $k => $v) : ?>
            <option value="<?php echo $v['id_anggota'];?>" <?php echo $default['id_anggota'] == $v['id_anggota'] ? 'selected' : ''; ?>><?php echo $v['nama']; ?></option>
          <?php endforeach; ?>
        </select><br>
        <?php echo $errorText['id_anggota']; ?>
      </p>
      <p>
        <label for="jumlah"><span class="mandatory">Jumlah</span></label><br>
        <input type="number" name="jumlah" id="jumlah" class="one-fourth <?php echo $errorClass['jumlah'];?>" value="<?php echo $default['jumlah']; ?>"><br>
        <?php echo $errorText['jumlah']; ?>
      </p>
      <p>
        <label for="tgl_peminjaman"><span class="mandatory">Tanggal peminjaman</span></label><br>
        <input type="date" name="tgl_peminjaman" id="tgl_peminjaman" class="one-fourth <?php echo $errorClass['tgl_peminjaman'];?>" value="<?php echo $default['tgl_peminjaman']; ?>"><br>
        <?php echo $errorText['tgl_peminjaman']; ?>
      </p>
      <p>
        <label for="tgl_pengembalian">Tanggal pengembalian</label><br>
        <input type="date" name="tgl_pengembalian" id="tgl_pengembalian" class="one-fourth <?php echo $errorClass['tgl_pengembalian'];?>" value="<?php echo $default['tgl_pengembalian']; ?>"><br>
        <?php echo $errorText['tgl_pengembalian']; ?>
      </p>
    </div>
  </div>
  
  <!-- Buttons -->
  <div class="buttons">
    <button class="button" name="submit" value="submit"><?php echo $data['button_label']?></button>
  </div>

</form>