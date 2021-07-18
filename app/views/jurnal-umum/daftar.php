<div class="heading">
  <h1>Daftar Jurnal Umum</h1>
  <div class="actions">
    <?php if ($data['id_rekening'] > 0 && $data['bulan'] != '' && $data['tahun'] != '') : ?>
      <a href="<?php echo BASE_URL;?>/jurnal-umum/form?id_rekening=<?php echo $data['id_rekening'];?>&bulan=<?php echo $data['bulan'];?>&tahun=<?php echo $data['tahun'];?>" class="button secondary">+ Buat jurnal umum baru</a>
    <?php else : ?>
      <button class="button secondary" disabled title="Rekening, bulan, dan tahun harus dipilih terlebih dahulu">+ Buat jurnal umum baru</button>
    <?php endif; ?>
  </div>
</div>

<!-- Flasher -->
<?php \App\Core\Flasher::show('jurnal-umum-daftar'); ?>

<form action="" method="get" class="filter noborder">
  <select name="id_rekening" id="tanggal">
    <option value="">Pilih rekening</option>
    <?php foreach($data['rekening'] as $k => $v) : ?>
      <option value="<?php echo $v['id_rekening'];?>" <?php echo $v['id_rekening'] == $data['id_rekening'] ? 'selected' : '';?>><?php echo $v['jenis_rekening'];?></option>
    <?php endforeach; ?>
  </select>

  <select name="bulan" id="bulan">
    <option value="">Pilih bulan</option>
    <?php for ($i = 1; $i <= count(\App\Core\Utilities::$monthNames); $i++) : ?>
      <option value="<?php echo $i; ?>" <?php echo $i == $data['bulan'] ? 'selected' : '';?>><?php echo \App\Core\Utilities::$monthNames[$i-1]; ?></option>
    <?php endfor;?>
  </select>

  <select name="tahun" id="tahun">
    <option value="">Pilih tahun</option>
    <?php for ($i = $data['minmax']['min']; $i <= $data['minmax']['max']; $i++) : ?>
      <option value="<?php echo $i; ?>" <?php echo $i == $data['tahun'] ? 'selected' : '';?>><?php echo $i; ?></option>
    <?php endfor;?>
  </select>

  <button class="button">Cari</button>
</form>

<table class="resp">
  <thead>
    <tr>
      <th width="0%">No</th>
      <th width="0%">Tanggal</th>
      <th width="100%">Keterangan</th>
      <th width="0%" class="align-right">Kredit</th>
      <th width="0%" class="align-right">Debit</th>
      <th width="0%" class="align-right">Saldo</th>
      <th width="0%"></th>
    </tr>
  </thead>
  <tbody>
    <?php
    
    $i = 0;
    $saldo = $data['previous_saldo']['jumlah'];
    
    ?>
    <?php foreach ($data['jurnal_umum'] as $k => $v): ?>
      <tr>
        <td data-label="No"><?php echo ++$i; ?></td>
        <td data-label="Tanggal" class="nowrap"><?php echo \App\Core\Utilities::formatDate($v['tanggal']); ?></td>
        <td data-label="Keterangan"><?php echo $v['nama']; ?> <?php echo $v['arus_kas'];?></td>
        <td data-label="Kredit" class="nowrap align-right">
          <?php
          
          if ($v['arus_kas'] == 'masuk') {
            echo 'Rp. ' . \App\Core\Utilities::formatRupiah($v['jumlah']);
            $saldo = $saldo + $v['jumlah'];
          }
          
          ?>
        </td>
        <td data-label="Debit" class="nowrap align-right">
        <?php
          
        if ($v['arus_kas'] == 'keluar') {
          echo 'Rp. ' . \App\Core\Utilities::formatRupiah($v['jumlah']);
          $saldo = $saldo - $v['jumlah'];
        }

        ?>
        </td>
        <td data-label="Saldo" class="nowrap align-right">Rp. <?php echo \App\Core\Utilities::formatRupiah($saldo); ?></td>
        <td>
          <div class="actions">
            <a href="<?php echo BASE_URL . '/jurnal-umum/form/' . $v['id_jurnal_umum'];?>"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#edit"></use></svg></a>
            <a href="<?php echo BASE_URL . '/jurnal-umum/hapus/' . $v['id_jurnal_umum'];?>" class="dangerous"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#delete"></use></svg></a>
          </div>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>