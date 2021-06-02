<?php include_once('header.php'); ?>

<table>
  <caption>Sedang menunggu validasi pindah muatan</caption>
  <tbody>
    <tr>
      <th width="33.333%">Jalur pengirim</td>
      <td width="66.666%"><?php echo $data['pindahan']['sj_1_nama_jalur_pengiriman']; ?></td>
    </tr>
    <tr>
      <th width="33.333%">Sopir pengirim 1</td>
      <td width="66.666%"><?php echo $data['pindahan']['sj_1_nama_1']; ?></td>
    </tr>
    <tr>
      <th width="33.333%">Sopir pengirim 2</td>
      <td width="66.666%"><?php echo $data['pindahan']['sj_1_nama_2']; ?></td>
    </tr>
    <tr>
      <th width="33.333%">Tabung besar</td>
      <td width="66.666%"><strong><?php echo $data['pindahan']['tabung_besar']; ?></strong> <span class="lighter-text">kantong</span></td>
    </tr>
    <tr>
      <th width="33.333%">Tabung kecil</td>
      <td width="66.666%"><strong><?php echo $data['pindahan']['tabung_kecil']; ?></strong> <span class="lighter-text">kantong</span></td>
    </tr>
    <tr>
      <th width="33.333%">Serut</td>
      <td width="66.666%"><strong><?php echo $data['pindahan']['serut']; ?></strong> <span class="lighter-text">kantong</span></td>
    </tr>
  </tbody>
</table>

<form method="post" action="" class="main">
  <div class="buttons space-between">
    <button class="button" name="validasi" value="submit">Validasi</button>
    <button class="button secondary" name="batalkan" value="submit">Batalkan</button>
  </div>
</form>