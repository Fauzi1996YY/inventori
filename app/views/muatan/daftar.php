<div class="heading">
  <h1>Muatan</h1>
  <div class="actions">
    <?php if ($data['can_add']): ?>
      <a href="<?php echo BASE_URL;?>/muatan/<?php echo $data['surat_jalan']['id_surat_jalan']; ?>/form" class="button secondary">+ Buat muatan baru</a>
    <?php endif; ?>
    <a href="<?php echo BASE_URL;?>/surat-jalan" class="button secondary">Kembali ke surat jalan</a>
  </div>
</div>

<!-- Flasher -->
<?php \App\Core\Flasher::show('muatan-daftar'); ?>

<table class="extra">
  <tbody>
    <tr>
      <td>
        <strong class="smaller-text lighter-text">Jalur</strong><br>
        <span class="value"><?php echo $data['surat_jalan']['nama_jalur_pengiriman'];?></span>
      </td>
      <td>
        <strong class="smaller-text lighter-text">Sopir 1</strong><br>
        <span class="value"><?php echo $data['surat_jalan']['nama_sopir_1'];?></span>
      </td>
      <td>
        <strong class="smaller-text lighter-text">Sopir 2</strong><br>
        <span class="value"><?php echo $data['surat_jalan']['nama_sopir_2'];?></span>
      </td>
    </tr>
  </tbody>
</table>

<?php $i = 1;?>
<?php foreach ($data['muatan'] as $k => $v): ?>

  <table class="resp">
    <caption class="smaller-text lighter-text">
      <div class="container">
        <div class="meta">
          <strong>Muatan <?php echo $i;?></strong><br>
          Waktu muat: <?php echo $v['waktu']; ?>
        </div>
        <div class="actions">
          <?php if ($v['validasi_muatan'] == 2 && $i == count($data['muatan'])) : ?>
            <a href="<?php echo BASE_URL . '/muatan/bongkar/' . $v['id_muatan'];?>" title="Bongkar muatan"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#anticlockwise"></use></svg></a>
          <?php else: ?>
            <span class="unclickable" title="Bongkar muatan"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#anticlockwise"></use></svg></span>
          <?php endif; ?>

          <?php if ($v['validasi_muatan'] < 1) : ?>
            <a href="<?php echo BASE_URL . '/muatan/form/' . $v['id_muatan'];?>"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#edit"></use></svg></a>
            <a href="<?php echo BASE_URL . '/muatan/hapus/' . $v['id_muatan'];?>" class="dangerous"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#delete"></use></svg></a>
          <?php else: ?>
            <span class="unclickable"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#edit"></use></svg></span>
            <span class="unclickable"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#delete"></use></svg></span>
          <?php endif;?>
        </div>
      </div>
    </caption>
    <thead>
      <tr>
        <th width="25%">Jenis barang</th>
        <th width="25%" class="align-center">Dimuat</th>
        <th width="25%" class="align-center">Kembali</th>
        <th width="25%" class="align-center">Rusak</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td data-label="Jenis barang">Es tabung besar</td>
        <td data-label="Dimuat" class="align-center"><?php echo $v['muatan_tabung_besar']; ?></td>
        <td data-label="Kembali" class="align-center"><?php echo $v['kembali_tabung_besar']; ?></td>
        <td data-label="Rusak" class="align-center"><?php echo $v['rusak_tabung_besar']; ?></td>
      </tr>
      <tr>
        <td data-label="Jenis barang">Es tabung kecil</td>
        <td data-label="Dimuat" class="align-center"><?php echo $v['muatan_tabung_kecil']; ?></td>
        <td data-label="Kembali" class="align-center"><?php echo $v['kembali_tabung_kecil']; ?></td>
        <td data-label="Rusak" class="align-center"><?php echo $v['rusak_tabung_kecil']; ?></td>
      </tr>
      <tr>
        <td data-label="Jenis barang">Es serut</td>
        <td data-label="Dimuat" class="align-center"><?php echo $v['muatan_serut']; ?></td>
        <td data-label="Kembali" class="align-center"><?php echo $v['kembali_serut']; ?></td>
        <td data-label="Rusak" class="align-center"><?php echo $v['rusak_serut']; ?></td>
      </tr>
    </tbody>
  </table>
  <?php $i++;?>

<?php endforeach; ?>

<!-- <table>
  <thead>
    <tr>
      <th class="align-center" rowspan="2" width="0%">Waktu</th>
      <th colspan="3" width="0%" class="align-center left-border right-border"><span class="smaller-text lighter-text">Dimuat</span></th>
      <th colspan="3" width="0%" class="align-center left-border right-border"><span class="smaller-text lighter-text">Rusak</span></th>
      <th rowspan="2" width="0%"></th>
    </tr>
    <tr>
      <th class="align-center">Tabung besar</th>
      <th class="align-center">Tabung kecil</th>
      <th class="right-border align-center">Serut</th>
      <th class="align-center">Tabung besar</th>
      <th class="align-center">Tabung kecil</th>
      <th class="align-center">Serut</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($data['muatan'] as $k => $v): ?>
      <tr <?php echo $v['validasi_muatan'] == 0 ? 'class="has-bg bg-pink" title="Belum divalidasi"' : ''; ?>>
        <td class="align-center"><?php echo $v['waktu']; ?></td>
        <td class="align-center"><?php echo $v['muatan_tabung_besar']; ?></td>
        <td class="align-center"><?php echo $v['muatan_tabung_kecil']; ?></td>
        <td class="align-center"><?php echo $v['muatan_serut']; ?></td>
        <td class="align-center"><?php echo $v['rusak_tabung_besar']; ?></td>
        <td class="align-center"><?php echo $v['rusak_tabung_kecil']; ?></td>
        <td class="align-center"><?php echo $v['rusak_serut']; ?></td>
        <td>
          <div class="actions">
            <?php if ($v['validasi_muatan'] < 1) : ?>
              <a href="<?php echo BASE_URL . '/muatan/form/' . $v['id_muatan'];?>"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#edit"></use></svg></a>
              <a href="<?php echo BASE_URL . '/muatan/hapus/' . $v['id_muatan'];?>" class="dangerous"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#delete"></use></svg></a>
            <?php else: ?>
              <span class="unclickable"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#edit"></use></svg></span>
              <span class="unclickable"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#delete"></use></svg></span>
            <?php endif;?>
          </div>
        </td>
      </tr>

    <?php endforeach; ?>
  </tbody>
</table> -->