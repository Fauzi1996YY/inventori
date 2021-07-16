<div class="heading">
  <h1>Daftar Kategori Jurnal</h1>
  <div class="actions">
    <a href="<?php echo BASE_URL;?>/kategori-jurnal/form" class="button secondary">+ Buat kategori baru</a>
  </div>
</div>

<!-- Flasher -->
<?php \App\Core\Flasher::show('kategori-jurnal-daftar'); ?>

<table class="resp">
  <thead>
    <tr>
      <th width="0%">Kode</th>
      <th width="0%">Nama</th>
      <th width="100%">Keterangan</th>
      <th width="0%" class="nowrap align-right">Jumlah sub</th>
      <th width="0%"></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($data['kategori_jurnal'] as $k => $v): ?>
      <tr>
        <td data-label="Kode" class="nowrap"><?php echo $v['kode']; ?></td>
        <td data-label="Nama" class="nowrap"><?php echo $v['nama']; ?></td>
        <td data-label="Keterangan"><?php echo $v['keterangan']; ?></td>
        <td data-label="Jumlah sub" class="nowrap align-right"><strong><a href="<?php echo BASE_URL . '/sub-kategori-jurnal/' . $v['id_kategori_jurnal'];?>"><?php echo $v['total_sub_kategori_jurnal']; ?> item</a></strong></td>
        <td>
          <div class="actions">
            <a href="<?php echo BASE_URL . '/kategori-jurnal/form/' . $v['id_kategori_jurnal'];?>"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#edit"></use></svg></a>
            <?php if ($v['total_sub_kategori_jurnal'] < 1): ?>
              <a href="<?php echo BASE_URL . '/kategori-jurnal/hapus/' . $v['id_kategori_jurnal'];?>" class="dangerous"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#delete"></use></svg></a>
            <?php else: ?>
              <span class="unclickable"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#delete"></use></svg></span>
            <?php endif; ?>
          </div>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>