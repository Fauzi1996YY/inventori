<div class="heading">
  <h1>Jalur Pengiriman</h1>
  <div class="actions">
    <a href="<?php echo BASE_URL;?>/jalur-pengiriman/form" class="button secondary">+ Buat jalur baru</a>
  </div>
</div>

<!-- Flasher -->
<?php \App\Core\Flasher::show('jalur-pengiriman-daftar'); ?>

<table class="resp">
  <thead>
    <tr>
      <th width="100%">Jalur pengiriman</th>
      <th width="0%"></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($data['jalur-pengiriman'] as $k => $v): ?>
      <tr>
        <td data-label="Jalur pengiriman"><?php echo $v['nama']; ?></td>
        <td>
          <div class="actions">
            <a href="<?php echo BASE_URL . '/jalur-pengiriman/form/' . $v['id_jalur_pengiriman'];?>"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#edit"></use></svg></a>
            <?php if ($v['total_surat_jalan'] < 1): ?>
              <a href="<?php echo BASE_URL . '/jalur-pengiriman/hapus/' . $v['id_jalur_pengiriman'];?>" class="dangerous"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#delete"></use></svg></a>
            <?php else: ?>
              <span class="unclickable"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#delete"></use></svg></span>
            <?php endif; ?>
          </div>
        </td>
      </tr>

    <?php endforeach; ?>
  </tbody>
</table>