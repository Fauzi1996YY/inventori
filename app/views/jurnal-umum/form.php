<?php

$errorClass = array(
  'id_kategori_jurnal' => '',
  'id_sub_kategori_jurnal' => '',
  'jumlah' => '',
  'tanggal' => ''
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
    <a href="<?php echo BASE_URL . '/jurnal-umum?id_rekening=' . $data['id_rekening'] . '&bulan=' . $data['bulan'] . '&tahun=' . $data['tahun']; ?>" class="button secondary">Kembali ke daftar</a>
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
<?php \App\Core\Flasher::show('jurnal-umum-form'); ?>

<table class="extra">
  <tbody class="tr">
    <td width="50%">
      <span class="smaller-text lighter-text">Rekening</span>
      <span class="value"><?php echo $data['rekening']['jenis_rekening'];?></span>
    </td>
    <td width="50%">
      <span class="smaller-text lighter-text">Bulan</span>
      <span class="value"><?php echo \App\Core\Utilities::$monthNames[(int)$data['bulan']] . ' ' . $data['tahun'];?></span>
    </td>
  </tbody>
</table>

<!-- Main form -->
<form method="post" action="" class="main">

  <div class="fieldset">
    <div class="fields">
      <p>
        <label for="tanggal"><span class="mandatory">Tanggal</span></label><br>
        <select name="tanggal" id="tanggal">
          <option value="">Pilih tanggal</option>
          <?php for($i = 1; $i <= 31; $i++) : ?>
            <?php $date = strlen($i) > 1 ? $i : '0' . $i;?>
            <option value="<?php echo $date; ?>" <?php echo $default['tanggal'] == $date ? 'selected' : '';?>><?php echo $date;?></option>
          <?php endfor;?>
        </select><br>
        <?php echo $errorText['tanggal']; ?>
      </p>
      <p>
        <label for="id_kategori_jurnal"><span class="mandatory">Kategori</span></label><br>
        <select name="id_kategori_jurnal" id="id_kategori_jurnal" class="<?php echo $errorClass['id_kategori_jurnal'];?>">
          <option value="">Pilih kategori</option>
          <?php foreach($data['kategori_jurnal'] as $k => $v) : ?>
            <option value="<?php echo $v['id_kategori_jurnal'];?>" <?php echo $default['id_kategori_jurnal'] == $v['id_kategori_jurnal'] ? 'selected' : '';?>><?php echo $v['nama'];?></option>
          <?php endforeach; ?>
        </select><br>
        <?php echo $errorText['id_kategori_jurnal']; ?>
      </p>
      <p>
        <label for="id_sub_kategori_jurnal"><span class="mandatory">Sub kategori jurnal</span></label><br>
        <select name="id_sub_kategori_jurnal" id="id_sub_kategori_jurnal" class="<?php echo $errorClass['id_sub_kategori_jurnal'];?>">
          <option value="">Pilih sub kategori jurnal</option>
        </select><br>
        <?php echo $errorText['id_sub_kategori_jurnal']; ?>
      </p>
      <p>
        <label for="jumlah"><span class="mandatory">Jumlah</span></label><br>
        <input type="number" name="jumlah" id="jumlah" class="one-third <?php echo $errorClass['jumlah'];?>" value="<?php echo $default['jumlah']; ?>"><br>
        <?php echo $errorText['jumlah']; ?>
      </p>
    </div>
  </div>
  
  <!-- Buttons -->
  <div class="buttons">
    <button class="button" name="submit" value="submit"><?php echo $data['button_label']?></button>
  </div>

</form>

<script>
var subKategoriJurnal = <?php echo json_encode($data['sub_kategori_jurnal_main_data']);?>;
var idSubKategoriJurnalValue = <?php echo $default['id_sub_kategori_jurnal']; ?>;
</script>