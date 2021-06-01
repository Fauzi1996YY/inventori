<div class="heading">
  <h1>Bongkar Muatan</h1>
  <div class="actions">
    <a href="<?php echo BASE_URL;?>/distribusi" class="button secondary">Kembali ke daftar</a>
  </div>
</div>

<!-- Flasher -->
<?php \App\Core\Flasher::show('bongkar-muatan-notifikasi'); ?>

<!-- Notification -->
<div class="notification warning">
  <span class="icon"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#exclamation"></use></svg></span>
  <h2>Setelah bongkar muatan diinisiasi, daftar pelanggan akan dihilangkan</h2>
  <p>Apakah anda yakin untuk menginisiasi bongkar muatan?</p>
  <form action="" method="post"><input type="submit" name="bongkar" value="Ya, bongkar muatan sekarang" class="button"></form>
</div>