<div class="heading">
  <h1><?php echo $_SESSION['role'] == 'admin' ? 'Detail Penjualan Per Surat Jalan' : 'Detail Penjualan Hari Ini';?></h1>
  <?php if ($_SESSION['role'] == 'admin') : ?>
    <div class="actions">
      <a href="<?php echo BASE_URL . '/penjualan'; ?>" class="button secondary">Kembali ke daftar</a>
    </div>
  <?php endif;?>
</div>

<table class="resp">
  <thead>
    <tr>
      <th width="20%">Tanggal</th>
      <th width="30%">Jalur pengiriman</th>
      <th width="25%">Kurir 1</th>
      <th width="25%">Kurir 2</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td data-label="Tanggal"><?php echo \App\Core\Utilities::formatDate($data['surat_jalan']['tanggal']);?></td>
      <td data-label="Jalur pengiriman"><?php echo $data['surat_jalan']['nama_jalur_pengiriman'];?></td>
      <td data-label="Kurir 1"><?php echo $data['surat_jalan']['nama_sopir_1'];?></td>
      <td data-label="Kurir 2"><?php echo $data['surat_jalan']['nama_sopir_2'];?></td>
    </tr>
  </tbody>
</table>

<table class="resp">
  <thead>
    <tr>
      <th width="0%" rowspan="2">No</th>
      <th width="80%" rowspan="2">Pelanggan</th>
      <th colspan="3" class="align-center left-border right-border">Barang terjual</th>
      <th width="0%" rowspan="2">Bonus</th>
      <th width="20%" rowspan="2" class="align-right">Total harga</th>
      <th width="0%" rowspan="2">Metode pembayaran</th>
      <?php if ($_SESSION['role'] == 'admin') : ?>
        <th width="0%" rowspan="2">&nbsp;</th>
      <?php endif;?>
    </tr>
    <tr>
      <th width="0%" class="left-border">Tabung besar</th>
      <th width="0%">Tabung kecil</th>
      <th width="0%" class="right-border">Es serut</th>
    </tr>
  </thead>
  <tbody>
    <?php
    
    $i = 1;
    $totalTabungBesar = 0;
    $totalTabungKecil = 0;
    $totalSerut = 0;
    $totalBonus = 0;
    $totalHarga = 0;
    
    ?>
    <?php foreach ($data['detail'] as $k => $v) : ?>
        <?php
        $totalTabungBesar += $v['es_tabung_besar'];
        $totalTabungKecil += $v['es_tabung_kecil'];
        $totalSerut += $v['es_serut'];
        $totalBonus += $v['bonus_es_tabung_kecil'];
        $totalHarga += $v['total_harga'];
        ?>
        <tr>
          <td data-label="No"><?php echo $i++;?></td>
          <td data-label="Pelanggan"><?php echo $v['nama'];?></td>
          <td data-label="Tabung besar"><?php echo $v['es_tabung_besar'];?></td>
          <td data-label="Tabung kecil"><?php echo $v['es_tabung_kecil'];?></td>
          <td data-label="Es serut"><?php echo $v['es_serut'];?></td>
          <td data-label="Bonus"><?php echo $v['bonus_es_tabung_kecil'];?></td>
          <td data-label="Total harga" class="align-right">Rp. <?php echo \App\Core\Utilities::formatRupiah($v['total_harga']);?></td>
          <td data-label="Metode pembayaran"><?php echo ucfirst($v['metode_pembayaran']);?></td>
          <?php if ($_SESSION['role'] == 'admin') : ?>
            <td data-label=""><a href="<?php echo BASE_URL . '/penjualan/edit/' . $v['id_penjualan'];?>"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#edit"></use></svg></a></td>
          <?php endif; ?>
        </tr>
    <?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr>
      <th class="align-right" colspan="2">Total</th>
      <th data-label="Tabung besar"><?php echo $totalTabungBesar;?></th>
      <th data-label="Tabung kecil"><?php echo $totalTabungKecil;?></th>
      <th data-label="Es serut"><?php echo $totalSerut;?></th>
      <th data-label="Bonus"><?php echo $totalBonus;?></th>
      <th data-label="Total harga" class="align-right">Rp. <?php echo \App\Core\Utilities::formatRupiah($totalHarga);?></th>
      <th data-label=""></th>
      <?php if ($_SESSION['role'] == 'admin') : ?>
        <th data-label=""></th>
      <?php endif;?>
    </tr>
  </tfoot>
</table>