<div class="heading">
  <h1>Hapus Jurnal Umum</h1>
  <div class="actions"><a href="<?php echo BASE_URL;?>/jurnal-umum?id_rekening=<?php echo $data['id_rekening'];?>&bulan=<?php echo $data['arr_tanggal'][1];?>&tahun=<?php echo $data['arr_tanggal'][0];?>" class="button secondary">Kembali ke daftar</a></div>
</div>

<!-- Notification -->
<div class="notification warning">
  <span class="icon"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#exclamation"></use></svg></span>
  <h2>Data yang telah dihapus tidak dapat dikembalikan lagi</h2>
  <p>Apakah anda yakin untuk menghapus jurnal umum dengan jenis rekening: <strong><?php echo $data['jenis_rekening']; ?></strong>,
    tanggal: <strong><?php echo \App\Core\Utilities::formatDate($data['tanggal']);?></strong>,
    sub kategori: <strong><?php echo $data['nama'];?></strong>,
    jumlah: <strong>Rp. <?php echo \App\Core\Utilities::formatRupiah($data['jumlah']);?></strong>?</p>
  <form action="" method="post"><input type="submit" name="hapus" value="Ya, hapus jurnal umum ini" class="button"></form>
</div>