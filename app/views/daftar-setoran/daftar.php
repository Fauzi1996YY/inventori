<div class="heading">
  <h1>Daftar Setoran</h1>
</div>

<div class="dual-container">
  <form action="" method="get" class="filter noborder">
    <select name="tanggal" id="tanggal">
      <option value="">Pilih tanggal</option>
      <?php for ($i = 1; $i <= 31; $i++) : ?>
        <option value="<?php echo $i; ?>" <?php echo $i == $data['tanggal'] ? 'selected' : '';?>><?php echo strlen($i) < 2 ? '0' . $i : $i; ?></option>
      <?php endfor;?>
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
  <a href="<?php echo BASE_URL . '/daftar-setoran?tanggal=' . $data['tanggal'] . '&bulan=' . $data['bulan'] . '&tahun=' . $data['tahun'] . '&pdf=true';?>" target="_blank" class="button secondary">Unduh PDF</a>
</div>

<table class="resp">
  <thead>
    <tr>
      <th width="40%">Jalur pengiriman</th>
      <th width="15%" class="align-right">Penerimaan cash</th>
      <th width="15%" class="align-right">Biaya operasional</th>
      <th width="15%" class="align-right">Kasbon</th>
      <th width="15%" class="align-right">Jumlah</th>
      <th width="0%" class="align-right"></th>
    </tr>
  </thead>
  <tbody>
    <?php
    $total_penerimaan_cash = 0;
    $total_biaya_operasional = 0;
    $total_kasbon = 0;
    $total_cash = 0;
    
    ?>
    <?php foreach ($data['setoran'] as $k => $v) : ?>
      <tr>
        <td><?php echo $v['nama_jalur_pengiriman']; ?></td>
        <td class="align-right" nowrap>Rp. <?php echo \App\Core\Utilities::formatRupiah($v['jumlah_cash'] + $v['jumlah_biaya_operasional'] + $v['jumlah_kasbon']); ?></td>
        <td class="align-right" nowrap>Rp. <?php echo \App\Core\Utilities::formatRupiah($v['jumlah_biaya_operasional']); ?></td>
        <td class="align-right" nowrap>Rp. <?php echo \App\Core\Utilities::formatRupiah($v['jumlah_kasbon']); ?></td>
        <td class="align-right" nowrap>Rp. <?php echo \App\Core\Utilities::formatRupiah($v['jumlah_cash']); ?></td>
        <td>
          <div class="actions">
            <a href="<?php echo BASE_URL . '/daftar-setoran/detail/' . $v['id_surat_jalan'];?>?tanggal=<?php echo $data['tanggal']; ?>&bulan=<?php echo $data['bulan']; ?>&tahun=<?php echo $data['tahun']; ?>"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#file-info"></use></svg></a>
          </div>
        </td>
      </tr>
      <?php
      $total_penerimaan_cash+= ($v['jumlah_cash'] + $v['jumlah_biaya_operasional'] + $v['jumlah_kasbon']);
      $total_biaya_operasional+= $v['jumlah_biaya_operasional'];
      $total_kasbon+= $v['jumlah_kasbon'];
      $total_cash+= $v['jumlah_cash'];
      ?>
    <?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr>
      <th class="align-right">Total</th>
      <th class="align-right" nowrap>Rp. <?php echo \App\Core\Utilities::formatRupiah($total_penerimaan_cash); ?></th>
      <th class="align-right" nowrap>Rp. <?php echo \App\Core\Utilities::formatRupiah($total_biaya_operasional); ?></th>
      <th class="align-right" nowrap>Rp. <?php echo \App\Core\Utilities::formatRupiah($total_kasbon); ?></th>
      <th class="align-right" nowrap>Rp. <?php echo \App\Core\Utilities::formatRupiah($total_cash); ?></th>
      <th class="align-right"></th>
    </tr>
  </tfoot>
</table>