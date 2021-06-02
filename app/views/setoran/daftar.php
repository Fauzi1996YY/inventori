<?php

$errorClass = array(
  'jumlah_cash' => ''
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

$amount_due = $data['total_cash'];

?>

<div class="heading">
  <h1>Setoran</h1>
  <div class="actions">
    <a href="<?php echo BASE_URL;?>/surat-jalan" class="button secondary">Kembali ke surat jalan</a>
  </div>
</div>

<!-- Flashed -->
<?php \App\Core\Flasher::show('setoran'); ?>

<!-- Data surat jalan -->
<table class="resp">
  <thead>
    <tr>
      <th width="25%">Tanggal</th>
      <th width="25%">Kurir 1</th>
      <th width="25%">Kurir 2</th>
      <th width="25%">Total penjualan cash</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td data-label="Tanggal"><?php echo \App\Core\Utilities::formatDate($data['surat_jalan']['tanggal']); ?></td>
      <td data-label="Kurir 1"><?php echo $data['surat_jalan']['nama_sopir_1']; ?></td>
      <td data-label="Kurir 2"><?php echo $data['surat_jalan']['nama_sopir_2']; ?></td>
      <td data-label="Total penjualan cash" class="nowrap">Rp. <?php echo \App\Core\Utilities::formatRupiah($data['total_cash']); ?></td>
    </tr>
  </tbody>
</table>

<!-- Biaya operasional -->
<table class="resp">
  <caption>
    <div class="container valign-middle">
      <div class="meta">Biaya operasional (kosongkan jika tidak ada)</div>
      <div class="actions">
        <a href="<?php echo BASE_URL . '/setoran/' . $data['surat_jalan']['id_surat_jalan'] . '/operasional/';?>" class="button secondary">+ Tambah biaya operasional</a>
      </div>
    </div>
  </caption>
  <thead>
    <tr>
      <th width="0%">No</th>
      <th width="70%">Keterangan</th>
      <th width="30%" class="align-right">Jumlah</th>
      <th width="0%"></th>
    </tr>
  </thead>
  <tbody>
    <?php
    $i = 1;
    $jumlah = 0;
    ?>
    <?php foreach ($data['operasional'] as $k => $v) : ?>
      <tr>
        <td data-label="No"><?php echo $i++;?></td>
        <td data-label="Keterangan"><?php echo $v['keterangan'];?></td>
        <td data-label="Jumlah" class="align-right nowrap">Rp. <?php echo \App\Core\Utilities::formatRupiah($v['jumlah']);?></td>
        <td>
          <div class="actions">
            <a href="<?php echo BASE_URL . '/setoran/' . $data['surat_jalan']['id_surat_jalan'] . '/operasional/' . $v['id_biaya_operasional'];?>"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#edit"></use></svg></a>
            <a href="<?php echo BASE_URL . '/setoran/' . $data['surat_jalan']['id_surat_jalan'] . '/hapus-operasional/' . $v['id_biaya_operasional'];?>" class="dangerous"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#delete"></use></svg></a>
          </div>
        </td>
      </tr>
      <?php $jumlah += $v['jumlah']; ?>
    <?php endforeach; ?>
    <?php $amount_due-= $jumlah; ?>
  </tbody>
  
  <?php if (count($data['operasional']) > 0) : ?>
    <tfoot>
      <tr>
        <th></th>
        <th class="align-right">Total</th>
        <th data-label="Total biaya" class="align-right">Rp. <?php echo \App\Core\Utilities::formatRupiah($jumlah);?></th>
        <th></th>
      </tr>
    </tfoot>
  <?php endif; ?>
</table>

<!-- Kasbon -->
<table class="resp">
    <caption>
      <div class="container valign-middle">
        <div class="meta">Kasbon (kosongkan jika tidak ada)</div>
        <div class="actions">
          <a href="<?php echo BASE_URL . '/setoran/' . $data['surat_jalan']['id_surat_jalan'] . '/kasbon/';?>" class="button secondary">+ Tambah kasbon</a>
        </div>
      </div>
    </caption>
  <thead>
    <tr>
      <th width="0%">No</th>
      <th width="70%">Nama karyawan</th>
      <th width="30%" class="align-right">Jumlah</th>
      <th width="0%"></th>
    </tr>
  </thead>
  <tbody>
    <?php
    $i = 1;
    $jumlah = 0;
    ?>
    <?php foreach ($data['kasbon'] as $k => $v) : ?>
      <tr>
        <td data-label="No"><?php echo $i++;?></td>
        <td data-label="Nama karyawan"><?php echo $v['nama'];?></td>
        <td data-label="Jumlah" class="align-right nowrap">Rp. <?php echo \App\Core\Utilities::formatRupiah($v['jumlah']);?></td>
        <td>
          <div class="actions">
            <a href="<?php echo BASE_URL . '/setoran/' . $data['surat_jalan']['id_surat_jalan'] . '/kasbon/' . $v['id_kasbon'];?>"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#edit"></use></svg></a>
            <a href="<?php echo BASE_URL . '/setoran/' . $data['surat_jalan']['id_surat_jalan'] . '/hapus-kasbon/' . $v['id_kasbon'];?>" class="dangerous"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#delete"></use></svg></a>
          </div>
        </td>
      </tr>
      <?php $jumlah += $v['jumlah']; ?>
    <?php endforeach; ?>

    <?php $amount_due-= $jumlah; ?>

    <?php if (count($data['kasbon']) > 0) : ?>
      <tfoot>
        <tr>
          <th></th>
          <th class="align-right">Total</th>
          <th data-label="Total kasbon" class="align-right">Rp. <?php echo \App\Core\Utilities::formatRupiah($jumlah);?></th>
          <th></th>
        </tr>
      </tfoot>
    <?php endif; ?>
  </tbody>
</table>

<form method="post" action="" class="main">

  <div class="fieldset">
    <div class="fields">
      <p>
        <label for="jumlah_cash"><span class="mandatory">Jumlah cash</span></label><br>
        <input type="number" name="jumlah_cash" id="jumlah_cash" class="one-fourth <?php echo $errorClass['jumlah_cash'];?>" value="<?php echo \App\Core\Utilities::formatRupiah($data['surat_jalan']['jumlah_cash']); ?>"><br>
        <span class="inline-help">Jumlah yang harus disetorkan: <strong>Rp. <?php echo \App\Core\Utilities::formatRupiah($amount_due); ?></strong></span>
        <?php echo $errorText['jumlah_cash']; ?>
      </p>
    </div>
  </div>

  <!-- Buttons -->
  <div class="buttons">
    <button class="button" name="submit" value="setor">Simpan setoran</button>
  </div>

</form>