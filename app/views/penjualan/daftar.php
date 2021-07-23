<div class="heading">
  <h1>Daftar Penjualan</h1>
</div>

<div class="dual-container">
  <form action="" method="get" class="filter noborder">
    <select name="tanggal" id="tanggal">
      <option value="">Pilih tanggal</option>
      <?php for ($i = 1; $i <= 31; $i++) : ?>
        <option value="<?php echo $i; ?>" <?php echo $i == $data['tanggal'] ? 'selected' : '';?>><?php echo strlen($i) < 2 ? '0' . $i : $i; ?></option>
      <?php endfor;?>
    </select>

    <select name="bulan" id="bulan">
      <option value="">Pilih bulan</option>
      <?php for ($i = 1; $i <= count(\App\Core\Utilities::$monthNames); $i++) : ?>
        <option value="<?php echo $i; ?>" <?php echo $i == $data['bulan'] ? 'selected' : '';?>><?php echo \App\Core\Utilities::$monthNames[$i-1]; ?></option>
      <?php endfor;?>
    </select>

    <select name="tahun" id="tahun">
      <option value="">Pilih tahun</option>
      <?php for ($i = $data['minmax']['min']; $i <= $data['minmax']['max']; $i++) : ?>
        <option value="<?php echo $i; ?>" <?php echo $i == $data['tahun'] ? 'selected' : '';?>><?php echo $i; ?></option>
      <?php endfor;?>
    </select>

    <button class="button">Cari</button>
  </form>
  <a href="<?php echo BASE_URL . '/penjualan?tanggal=' . $data['tanggal'] . '&bulan=' . $data['bulan'] . '&tahun=' . $data['tahun'] . '&pdf=true';?>" target="_blank" class="button secondary">Unduh PDF</a>
</div>

<table class="resp">
  <thead>
    <tr>
      <th width="100%" rowspan="2">Jalur pengiriman</th>
      <th width="0%" colspan="3" class="align-center left-border right-border">Barang terjual</th>
      <th width="0%" rowspan="2" class="align-right">Cash</th>
      <th width="0%" rowspan="2" class="align-right">Invoice</th>
      <th width="0%"></th>
    </tr>
    <tr>
      <th width="0%" class="align-center left-border">Tabung besar</th>
      <th width="0%" class="align-center">Tabung kecil</th>
      <th width="0%" class="align-center right-border">Serut</th>
      <th width="0%"></th>
    </tr>
  </thead>
  <?php
  $totalTabungBesar = 0;
  $totalTabungKecil = 0;
  $totalSerut = 0;
  $totalCash = 0;
  $totalInvoice = 0;
  ?>
  <tbody>
    <?php foreach ($data['penjualan'] as $k => $v) : ?>

      <tr>
        <td data-label="Jalur pengiriman"><?php echo $v['nama'];?></td>
        <td data-label="Tabung besar" class="align-center"><?php echo $v['tabung_besar'];?></td>
        <td data-label="Tabung kecil" class="align-center"><?php echo $v['tabung_kecil'];?></td>
        <td data-label="Serut" class="align-center"><?php echo $v['serut'];?></td>
        <td data-label="Cash" class="align-right nowrap">Rp. <?php echo \App\Core\Utilities::formatRupiah($v['cash']);?></td>
        <td data-label="Invoice" class="align-right nowrap">Rp. <?php echo \App\Core\Utilities::formatRupiah($v['invoice']);?></td>
        <td>
          <div class="actions">
            <a href="<?php echo BASE_URL . '/penjualan/' . $v['id_surat_jalan'];?>"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#file-info"></use></svg></a>
          </div>
        </td>
      </tr>
      <?php
      $totalTabungBesar += $v['tabung_besar'];
      $totalTabungKecil += $v['tabung_kecil'];
      $totalSerut += $v['serut'];
      $totalCash += $v['cash'];
      $totalInvoice += $v['invoice'];
      ?>
    <?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr>
      <th class="align-right">Total</th>
      <th data-label="Tabung besar" class="align-center"><?php echo $totalTabungBesar;?></th>
      <th data-label="Tabung kecil" class="align-center"><?php echo $totalTabungKecil;?></th>
      <th data-label="Serut" class="align-center"><?php echo $totalSerut;?></th>
      <th data-label="Cash" class="align-right nowrap">Rp. <?php echo \App\Core\Utilities::formatRupiah($totalCash);?></th>
      <th data-label="Invoice" class="align-right nowrap">Rp. <?php echo \App\Core\Utilities::formatRupiah($totalInvoice);?></th>
      <th></th>
    </tr>
  </tfoot>
</table>