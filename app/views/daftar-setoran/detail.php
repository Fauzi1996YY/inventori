<div class="heading">
  <h1>Detail Setoran</h1>
  <div class="actions">
    <a href="<?php echo BASE_URL;?>/daftar-setoran<?php echo $data['qs_back_to_list']; ?>" class="button secondary">Kembali ke daftar</a>
  </div>
</div>

<table class="resp">
  <thead>
    <tr>
      <th width="25%">Tanggal</th>
      <th width="25%">Kurir 1</th>
      <th width="25%">Kurir 2</th>
      <th width="25%" class="align-right">Total penjualan cash</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td data-label="Tanggal" nowrap><?php echo \App\Core\Utilities::formatDate($data['surat_jalan']['tanggal']);?></td>
      <td data-label="Kurir 1"><?php echo $data['surat_jalan']['nama_sopir_1'];?></td>
      <td data-label="Kurir 2"><?php echo $data['surat_jalan']['nama_sopir_2'];?></td>
      <td data-label="Total penjualan cash" class="align-right"><strong>Rp. <?php echo \App\Core\Utilities::formatRupiah($data['surat_jalan']['jumlah_cash'] + $data['total_biaya_operasional'] + $data['total_kasbon']);?></strong></td>
    </tr>
  </tbody>
</table>

<table class="resp">
  <caption><strong>Biaya Operasional</strong></caption>
  <thead>
    <tr>
      <th width="0%">No</th>
      <th width="100%">Keterangan</th>
      <th width="0%" class="align-right">Jumlah</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $i = 1;
    $total_operasional = 0;
    ?>
    <?php foreach ($data['biaya_operasional'] as $k => $v) : ?>
      <tr>
        <td data-label="No"><?php echo $i++; ?></td>
        <td data-label="Keterangan"><?php echo $v['keterangan']; ?></td>
        <td data-label="Jumlah" class="align-right" nowrap>Rp. <?php echo \App\Core\Utilities::formatRupiah($v['jumlah']); ?></td>
      </tr>
      <?php $total_operasional+=$v['jumlah']; ?>
    <?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr>
      <th colspan="2" class="align-right" data-label="Total">Total</th>
      <th class="align-right" nowrap>Rp. <?php echo \App\Core\Utilities::formatRupiah($total_operasional); ?></th>
    </tr>
  </tfoot>
</table>

<table class="resp">
  <caption><strong>Kasbon</strong></caption>
  <thead>
    <tr>
      <th width="0%">No</th>
      <th width="100%">Keterangan</th>
      <th width="0%" class="align-right">Jumlah</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $i = 1;
    $total_kasbon = 0;
    ?>
    <?php foreach ($data['kasbon'] as $k => $v) : ?>
      <tr>
        <td data-label="No"><?php echo $i++; ?></td>
        <td data-label="Keterangan"><?php echo $v['nama']; ?></td>
        <td data-label="Jumlah" class="align-right" nowrap>Rp. <?php echo \App\Core\Utilities::formatRupiah($v['jumlah']); ?></td>
      </tr>
      <?php $total_kasbon+=$v['jumlah']; ?>
    <?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr>
      <th colspan="2" class="align-right" data-label="Total">Total</th>
      <th class="align-right" nowrap>Rp. <?php echo \App\Core\Utilities::formatRupiah($total_kasbon); ?></th>
    </tr>
  </tfoot>
</table>

<table class="resp">
  <tbody>
    <tr>
      <th class="align-right">Jumlah setoran: <strong>Rp. <?php echo \App\Core\Utilities::formatRupiah($data['surat_jalan']['jumlah_cash']);?></strong></th>
    </tr>
  </tbody>
</table>