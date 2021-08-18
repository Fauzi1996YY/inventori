<div class="heading">
  <h1>Daftar Pembelian</h1>
</div>

<form action="" method="get" class="filter noborder">
  <select name="bulan" id="bulan">
    <option value="0">Sepanjang tahun</option>
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

<table class="resp">
  <thead>
    <tr>
      <th width="0%"><?php echo $data['bulan'] < 1 ? 'Bulan' : 'Tanggal'; ?></th>
      <th width="0%" class="align-right">Tabung besar</th>
      <th width="0%" class="align-right">Tabung kecil</th>
      <th width="0%" class="align-right">Serut</th>
      <th width="0%" class="align-right">Berat</th>
      <th width="0%" class="align-right">Harga</th>
    </tr>
  </thead>
  <tbody>
    
    <?php
    $jumlah_tabung_besar = 0;
    $jumlah_tabung_kecil = 0;
    $jumlah_serut = 0;
    $jumlah_total_berat = 0;
    $jumlah_harga = 0;
    ?>

    <?php foreach ($data['penjualan'] as $k => $v) : ?>

      <?php
      $jumlah_tabung_besar+= $v['tabung_besar'];
      $jumlah_tabung_kecil+= $v['tabung_kecil'];
      $jumlah_serut+= $v['serut'];
      $jumlah_total_berat+= $v['berat_total'];
      $jumlah_harga+= $v['total_harga'];
      ?>

      <tr>
        <?php $when = \App\Core\Utilities::formatDate($v['tanggal']); ?>
        <td data-label="Tanggal" nowrap><?php echo $data['bulan'] < 1 ? substr($when, 3) : $when; ?></td>
        <td class="align-right" data-label="Tabung besar" nowrap><?php echo $v['tabung_besar']; ?> kantong</td>
        <td class="align-right" data-label="Tabung kecil" nowrap><?php echo $v['tabung_kecil']; ?> kantong</td>
        <td class="align-right" data-label="Serut" nowrap><?php echo $v['serut']; ?> kantong</td>
        <td class="align-right" data-label="Berat" nowrap><?php echo $v['berat_total']; ?> Kg</td>
        <td class="align-right" data-label="Harga" nowrap>Rp. <?php echo \App\Core\Utilities::formatRupiah($v['total_harga']); ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
  <tfoot>
    <th>Total</th>
    <th class="align-right" nowrap data-label="Tabung besar"><?php echo $jumlah_tabung_besar; ?> Kantong</th>
    <th class="align-right" nowrap data-label="Tabung kecil"><?php echo $jumlah_tabung_kecil; ?> Kantong</th>
    <th class="align-right" nowrap data-label="Serut"><?php echo $jumlah_serut; ?> Kantong</th>
    <th class="align-right" nowrap data-label="Berat"><?php echo $jumlah_total_berat; ?> Kg</th>
    <th class="align-right" nowrap data-label="Harga">Rp. <?php echo \App\Core\Utilities::formatRupiah($jumlah_harga); ?></th>
  </tfoot>
</table>