<div class="heading">
  <h1>Daftar Sub Kategori Jurnal</h1>
  <div class="actions">
    <a href="<?php echo BASE_URL;?>/kategori-jurnal/" class="button secondary">Kembali ke daftar kategori</a>
    <a href="<?php echo BASE_URL;?>/sub-kategori-jurnal/form/<?php echo $data['kategori_jurnal']['id_kategori_jurnal']; ?>" class="button secondary">+ Buat sub kategori baru</a>
  </div>
</div>

<!-- Flasher -->
<?php \App\Core\Flasher::show('sub-kategori-jurnal-daftar'); ?>

<table class="resp">
  <caption>Nama kategori: <strong><?php echo $data['kategori_jurnal']['nama'];?></strong></caption>
  <thead>
    <tr>
      <th width="0%">Kode</th>
      <th width="0%">Nama</th>
      <th width="0%" class="nowrap">Arus kas</th>
      <th width="100%">Keterangan</th>
      <th width="0%"></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($data['sub_kategori_jurnal'] as $k => $v): ?>
      <tr>
        <td data-label="Kode" class="nowrap"><?php echo $v['kode']; ?></td>
        <td data-label="Nama" class="nowrap"><?php echo $v['nama']; ?></td>
        <td data-label="Arus kas" class="nowrap"><?php echo $v['arus_kas']; ?></td>
        <td data-label="Keterangan"><?php echo $v['keterangan']; ?></td>
        <td>
          <div class="actions">
            <a href="<?php echo BASE_URL . '/sub-kategori-jurnal/form/' . $v['id_kategori_jurnal'] . '/' . $v['id_sub_kategori_jurnal'];?>"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#edit"></use></svg></a>
            <?php if ($v['total_jurnal_umum'] < 1): ?>
              <a href="<?php echo BASE_URL . '/sub-kategori-jurnal/hapus/' . $v['id_sub_kategori_jurnal'];?>" class="dangerous"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#delete"></use></svg></a>
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