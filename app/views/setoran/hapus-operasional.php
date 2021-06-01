<div class="heading">
  <h1>Hapus Biaya Operasional</h1>
  <div class="actions"><a href="<?php echo BASE_URL;?>/setoran/<?php echo $data['surat_jalan']['id_surat_jalan'];?>/" class="button secondary">Kembali ke daftar</a></div>
</div>

<!-- Notification -->
<div class="notification warning">
  <span class="icon"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#exclamation"></use></svg></span>
  <h2>Data yang telah dihapus tidak dapat dikembalikan lagi</h2>
  <p>Apakah anda yakin untuk menghapus biaya operasional dengan keterangan: <strong><?php echo $data['operasional']['keterangan']; ?></strong>?</p>
  <form action="" method="post"><input type="submit" name="hapus" value="Ya, hapus biaya operasional ini" class="button"></form>
</div>