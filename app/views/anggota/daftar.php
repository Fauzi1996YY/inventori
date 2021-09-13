<div class="heading">
  <h1>Daftar Anggota</h1>
  <div class="actions">
    <a href="<?php echo BASE_URL;?>/anggota/form" class="button secondary">+ Buat Anggota baru</a>
  </div>
</div>

<!-- Flasher -->
<?php \App\Core\Flasher::show('anggota-daftar'); ?>

<table class="resp">
  <thead>
    <tr>
      <th width="40%">Nama</th>
      <th width="60%">Keterangan</th>
      <th width="0%"></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($data['anggota'] as $k => $v): ?>
      <tr>
        <td data-label="Nama" class="nowrap"><?php echo $v['nama']; ?></td>
        <td data-label="Keterangan"><?php echo $v['keterangan']; ?></td>
        <td>
          <div class="actions">
            <a href="<?php echo BASE_URL . '/anggota/form/' . $v['id_anggota'];?>"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#edit"></use></svg></a>
            <a href="<?php echo BASE_URL . '/anggota/hapus/' . $v['id_anggota'];?>" class="dangerous"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#delete"></use></svg></a>
          </div>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- Pagination -->
<?php echo isset($data['paging']) ? $data['paging'] : ''; ?>