<div class="heading">
  <h1>Hapus Muatan</h1>
  <div class="action-buttons"><a href="<?php echo BASE_URL;?>/muatan/<?php echo $data['id_surat_jalan'];?>" class="button secondary">Kembali ke daftar</a></div>
</div>

<!-- Notification -->
<div class="notification warning">
  <span class="icon"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#exclamation"></use></svg></span>
  <h2>Data yang telah dihapus tidak dapat dikembalikan lagi</h2>
  <p>Apakah anda yakin untuk menghapus muatan untuk tanggal <strong><?php echo \App\Core\Utilities::formatDate($data['tanggal']);?></strong>?</p>
  <form action="" method="post"><input type="submit" name="hapus" value="Ya, hapus muatan ini" class="button"></form>
</div>