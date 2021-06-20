<div class="heading">
  <h1>Hapus Penjualan</h1>
  <div class="actions"><a href="<?php echo BASE_URL;?>/penjualan/<?php echo $data['penjualan']['id_surat_jalan'];?>" class="button secondary">Kembali ke daftar</a></div>
</div>

<!-- Notification -->
<div class="notification warning">
  <span class="icon"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#exclamation"></use></svg></span>
  <h2>Data yang telah dihapus tidak dapat dikembalikan lagi</h2>
  <p>Apakah anda yakin untuk menghapus penjualan dengan data di bawah:</p>
  <p>
  <pre class="normal-text">
Tanggal           : <strong><?php echo \App\Core\Utilities::formatDate($data['surat_jalan']['tanggal']);?></strong>
Nama Sopir 1      : <strong><?php echo $data['surat_jalan']['nama_sopir_1'];?></strong>
Nama Sopir 2      : <strong><?php echo $data['surat_jalan']['nama_sopir_2'];?></strong>
Nama pelanggan    : <strong><?php echo $data['penjualan']['nama_pembeli'];?></strong>
Es tabung besar   : <strong><?php echo $data['penjualan']['es_tabung_besar'];?></strong> kantong
Es tabung kecil   : <strong><?php echo $data['penjualan']['es_tabung_kecil'];?></strong> kantong
Es serut          : <strong><?php echo $data['penjualan']['es_serut'];?></strong> kantong
  </pre>
  </p>
  <form action="" method="post"><input type="submit" name="hapus" value="Ya, hapus penjualan ini" class="button"></form>
</div>