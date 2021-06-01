<div class="heading">
  <h1>Pelanggan</h1>
  <div class="actions">
    <a href="<?php echo BASE_URL;?>/pelanggan/form" class="button secondary">+ Buat pelanggan baru</a>
  </div>
</div>

<!-- Flasher -->
<?php \App\Core\Flasher::show('pelanggan-daftar'); ?>

<form action="" method="get" class="filter">
  <input type="text" name="s" id="s" value="<?php echo $data['filter']['search']; ?>" placeholder="Ketik nama, no. telp, atau alamat yang ingin dicari" autocomplete="off">
  <input type="submit" class="button" value="Cari Pelanggan">
</form>

<table class="resp">
  <thead>
    <tr>
      <th width="30%"><a href="<?php echo $data['order']['nama']['link']; ?>" class="<?php echo $data['order']['nama']['classname']; ?>">Nama</a></th>
      <th width="30%"><a href="<?php echo $data['order']['nama_jalur_pengiriman']['link']; ?>" class="<?php echo $data['order']['nama_jalur_pengiriman']['classname']; ?>">Jalur pengiriman</a></th>
      <th width="40%"><a href="<?php echo $data['order']['alamat']['link']; ?>" class="<?php echo $data['order']['alamat']['classname']; ?>">Alamat</a></th>
      <th width="0%"></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($data['pelanggan'] as $k => $v): ?>
      <tr>
        <td data-label="Nama"><span class="left-dot <?php echo $v['username'] == '' ? 'red' : '';?>"><?php echo $v['nama']; ?></span></td>
        <td data-label="Jalur pengiriman"><?php echo $v['nama_jalur_pengiriman']; ?></td>
        <td data-label="Alamat"><?php echo $v['alamat']; ?>&nbsp;</td>
        <td>
          <div class="actions">
            <a href="<?php echo BASE_URL . '/pelanggan/pin/' . $v['id_user'];?>"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#fingerprint"></use></svg></a>
            <a href="<?php echo BASE_URL . '/pelanggan/form/' . $v['id_user'];?>"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#edit"></use></svg></a>
            <?php if ($v['total_penjualan'] > 0):?>
              <span class="unclickable"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#delete"></use></svg></span>
            <?php else:?>
              <a href="<?php echo BASE_URL . '/pelanggan/hapus/' . $v['id_user'];?>" class="dangerous"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#delete"></use></svg></a>
            <?php endif;?>            
          </div>
        </td>
      </tr>

    <?php endforeach; ?>
  </tbody>
</table>

<!-- Pagination -->
<?php echo isset($data['paging']) ? $data['paging'] : ''; ?>