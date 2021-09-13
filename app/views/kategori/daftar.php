<div class="heading">
  <h1>Daftar Kategori</h1>
  <div class="actions">
    <a href="<?php echo BASE_URL;?>/kategori/form" class="button secondary">+ Buat kategori baru</a>
  </div>
</div>

<!-- Flasher -->
<?php \App\Core\Flasher::show('kategori-daftar'); ?>

<table class="resp">
  <thead>
    <tr>
      <th width="0%">Kode</th>
      <th width="0%">Nama</th>
      <th width="100%">Keterangan</th>
      <th width="0%" class="nowrap align-right">Jumlah barang</th>
      <th width="0%"></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($data['kategori'] as $k => $v): ?>
      <tr>
        <td data-label="Kode" class="nowrap"><?php echo $v['kode']; ?></td>
        <td data-label="Nama" class="nowrap"><?php echo $v['nama']; ?></td>
        <td data-label="Keterangan"><?php echo $v['keterangan']; ?></td>
        <td data-label="Jumlah barang" class="nowrap align-right"><strong><a href="<?php echo BASE_URL . '/barang/' . $v['id_kategori'];?>"><?php echo $v['total_barang']; ?> item</a></strong></td>
        <td>
          <div class="actions">
            <a href="<?php echo BASE_URL . '/kategori/form/' . $v['id_kategori'];?>"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#edit"></use></svg></a>
            <?php if ($v['total_barang'] < 1): ?>
              <a href="<?php echo BASE_URL . '/kategori/hapus/' . $v['id_kategori'];?>" class="dangerous"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#delete"></use></svg></a>
            <?php else: ?>
              <span class="unclickable"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#delete"></use></svg></span>
            <?php endif; ?>
          </div>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- Pagination -->
<?php echo isset($data['paging']) ? $data['paging'] : ''; ?>