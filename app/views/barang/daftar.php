<div class="heading">
  <h1>Daftar Barang</h1>
  <div class="actions">
    <a href="<?php echo BASE_URL;?>/barang/form" class="button secondary">+ Buat barang baru</a>
  </div>
</div>

<!-- Flasher -->
<?php \App\Core\Flasher::show('barang-daftar'); ?>

<table class="resp">
  <thead>
    <tr>
      <th width="25%">Kode</th>
      <th width="25%">Nama</th>
      <th width="25%">Brand</th>
      <th width="25%">Kategori</th>
      <th width="0%"></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($data['barang'] as $k => $v): ?>
      <tr>
        <td data-label="Kode" class="nowrap"><?php echo $v['kode']; ?></td>
        <td data-label="Nama" class="nowrap"><?php echo $v['nama']; ?></td>
        <td data-label="Brand"><?php echo $v['brand']; ?></td>
        <td data-label="Kategori"><?php echo $v['nama_kategori']; ?></td>
        <td>
          <div class="actions">
            <a href="<?php echo BASE_URL . '/barang/form/' . $v['id_barang'];?>"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#edit"></use></svg></a>
            <a href="<?php echo BASE_URL . '/barang/hapus/' . $v['id_barang'];?>" class="dangerous"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#delete"></use></svg></a>
          </div>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- Pagination -->
<?php echo isset($data['paging']) ? $data['paging'] : ''; ?>