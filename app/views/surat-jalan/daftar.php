<div class="heading">
  <h1>Surat Jalan</h1>
  <div class="actions">
    <a href="<?php echo BASE_URL;?>/surat-jalan/form" class="button secondary">+ Buat surat jalan baru</a>
  </div>
</div>

<!-- Flasher -->
<?php \App\Core\Flasher::show('surat-jalan-daftar'); ?>

<table class="resp">
  <thead>
    <tr>
      <th width="0%">Tanggal</th>
      <th width="0%">Jalur pengiriman</th>
      <th width="0%">Kurir 1</th>
      <th width="0%">Kurir 2</th>
      <th width="0%"></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($data['surat_jalan'] as $k => $v): ?>
      <tr <?php echo $v['tanggal'] != date('Y-m-d') ? 'class="has-bg bg-pink" title="Setoran belum ada"' : '';?>>
        <td data-label="Tanggal" class="nowrap"><?php echo \App\Core\Utilities::formatDate($v['tanggal']); ?></td>
        <td data-label="Jalur pengiriman"><?php echo $v['nama_jalur_pengiriman']; ?></td>
        <td data-label="Kurir 1"><?php echo $v['nama_sopir_1']; ?></td>
        <td data-label="Kurir 2"><?php echo $v['nama_sopir_2']; ?></td>
        <td>
          <div class="actions">
            <!-- Setoran -->
            <?php if ($v['validasi_setoran'] < 1 && ($v['total_muatan'] > 0 && $v['total_muatan'] == $v['total_muatan_selesai'])) : ?>
              <a href="<?php echo BASE_URL . '/setoran/' . $v['id_surat_jalan'];?>"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#hand-coin"></use></svg></a>
            <?php else: ?>
              <span class="unclickable"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#hand-coin"></use></svg></span>
            <?php endif;?>
            
            <!-- Daftar muatan -->
            <?php if ($v['validasi_setoran'] > 0) : ?>
              <span class="unclickable"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#clockwise"></use></svg></span>
            <?php else: ?>
              <a href="<?php echo BASE_URL . '/muatan/' . $v['id_surat_jalan'];?>"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#clockwise"></use></svg></a>
            <?php endif;?>
            
            <!-- Edit surat jalan -->
            <?php if ($v['total_muatan_tervalidasi'] < 1) : ?>
              <a href="<?php echo BASE_URL . '/surat-jalan/form/' . $v['id_surat_jalan'];?>"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#edit"></use></svg></a>
            <?php else: ?>
              <span class="unclickable"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#edit"></use></svg></span>
            <?php endif;?>

            <!-- Hapus surat jalan -->
            <?php if ($v['total_muatan'] < 1) : ?>
              <a href="<?php echo BASE_URL . '/surat-jalan/hapus/' . $v['id_surat_jalan'];?>" class="dangerous"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#delete"></use></svg></a>
            <?php else: ?>
              <span class="unclickable"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#delete"></use></svg></span>
            <?php endif;?>

          </div>
        </td>
      </tr>

    <?php endforeach; ?>
  </tbody>
</table>