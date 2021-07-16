<div class="heading">
  <h1>Daftar Rekening</h1>
  <div class="actions">
    <a href="<?php echo BASE_URL;?>/rekening/form" class="button secondary">+ Buat rekening baru</a>
  </div>
</div>

<!-- Flasher -->
<?php \App\Core\Flasher::show('rekening-daftar'); ?>

<table class="resp">
  <thead>
    <tr>
      <th width="0%">No</th>
      <th width="0%">Bank</th>
      <th width="0%">Kantor cabang</th>
      <th width="0%">Nomor rekening</th>
      <th width="0%">Nama pemilik rekening</th>
      <th width="0%">Jenis rekening</th>
      <th width="0%"></th>
    </tr>
  </thead>
  <tbody>
    <?php $i = 0;?>
    <?php foreach ($data['rekening'] as $k => $v): ?>
      <tr>
        <td data-label="No"><?php echo ++$i; ?></td>
        <td data-label="Bank"><?php echo $v['bank']; ?></td>
        <td data-label="Kantor cabang"><?php echo $v['kantor_cabang']; ?></td>
        <td data-label="Nomor rekening"><?php echo $v['nomor_rekening']; ?></td>
        <td data-label="Nama pemilik cabang"><?php echo $v['nama_pemilik_rekening']; ?></td>
        <td data-label="Jenis rekening"><?php echo $v['jenis_rekening']; ?></td>
        <td>
          <div class="actions">
            <a href="<?php echo BASE_URL . '/rekening/form/' . $v['id_rekening'];?>"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#edit"></use></svg></a>
            <?php if ($v['total_jurnal_umum'] < 1): ?>
              <a href="<?php echo BASE_URL . '/rekening/hapus/' . $v['id_rekening'];?>" class="dangerous"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#delete"></use></svg></a>
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