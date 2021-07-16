<div class="heading">
  <h1>Hapus Kategori Jurnal</h1>
  <div class="actions"><a href="<?php echo BASE_URL;?>/kategori-jurnal/" class="button secondary">Kembali ke daftar</a></div>
</div>

<!-- Notification -->
<div class="notification warning">
  <span class="icon"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#exclamation"></use></svg></span>
  <h2>Data yang telah dihapus tidak dapat dikembalikan lagi</h2>
  <p>Apakah anda yakin untuk menghapus kategori jurnal dengan nama: <strong><?php echo $data['nama']; ?></strong>?</p>
  <form action="" method="post"><input type="submit" name="hapus" value="Ya, hapus rekening ini" class="button"></form>
</div>