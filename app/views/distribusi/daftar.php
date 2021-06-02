<div class="heading">
  <h1>Distribusi</h1>
  
  <?php if (!$data['invalidated_muatan']) : ?>
    <div class="actions">
      <a href="<?php echo BASE_URL;?>/pindah-muatan/" class="button secondary">Pindah muatan</a>
      <a href="<?php echo BASE_URL;?>/bongkar-muatan/" class="button secondary">Bongkar muatan</a>
      <a href="<?php echo BASE_URL;?>/distribusi/form" class="button secondary">+ Buat nota pelanggan baru</a>
    </div>
  <?php endif;?>
  
</div>

<!-- Flasher -->
<?php \App\Core\Flasher::show('distribusi-daftar'); ?>

<table class="extra">
  <tbody class="tr">
    <td width="33.333%">
      <span class="smaller-text lighter-text">Es tabung besar</span>
      <span class="value"><?php echo $data['sisa_muatan']['es_tabung_besar'];?></span>
    </td>
    <td width="33.333%">
      <span class="smaller-text lighter-text">Es tabung kecil</span>
      <span class="value"><?php echo $data['sisa_muatan']['es_tabung_kecil'];?></span>
    </td>
    <td width="33.333%">
      <span class="smaller-text lighter-text">Es serut</span>
      <span class="value"><?php echo $data['sisa_muatan']['es_serut'];?></span>
    </td>
  </tbody>
</table>

<?php if ($data['invalidated_muatan']) : ?>
  <form action="" method="post" class="align-center">
    <input type="submit" name="validasi_muatan" value="Validasi muatan" class="button">
  </form><br>
  <?php return;?>
<?php endif;?>

<form action="" method="get" class="filter">
  <input type="text" name="s" id="s" value="<?php echo $data['filter']['search']; ?>" placeholder="Ketik nama, no. telp, atau alamat yang ingin dicari" autocomplete="off">
  <button class="button">Cari</button>
</form>

<table class="resp">
  <thead>
    <tr>
      <th scope="col" width="30%"><a href="<?php echo $data['order']['nama']['link']; ?>" class="<?php echo $data['order']['nama']['classname']; ?>">Nama</a></th>
      <th scope="col" width="40%"><a href="<?php echo $data['order']['alamat']['link']; ?>" class="<?php echo $data['order']['alamat']['classname']; ?>">Alamat</a></th>
      <th scope="col" width="0%"></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($data['pelanggan'] as $k => $v): ?>
      <tr>
        <td data-label="Nama"><?php echo $v['nama']; ?></td>
        <td data-label="Alamat"><?php echo $v['alamat']; ?></td>
        <td data-label="">
          <div class="actions">
            <a href="<?php echo BASE_URL . '/distribusi/form/' . $v['id_user'];?>"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#hand-coin"></use></svg></a>
          </div>
        </td>
      </tr>

    <?php endforeach; ?>
  </tbody>
</table>