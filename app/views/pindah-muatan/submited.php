<?php include_once('header.php'); ?>

<table>
  <caption>Sedang menunggu validasi dari penerima</caption>
  <tbody>
    <tr>
      <th width="33.333%">Jalur penerima</td>
      <td width="66.666%"><?php echo $data['pindahan']['sj_2_nama_jalur_pengiriman']; ?></td>
    </tr>
    <tr>
      <th width="33.333%">Sopir penerima 1</td>
      <td width="66.666%"><?php echo $data['pindahan']['sj_2_nama_1']; ?></td>
    </tr>
    <tr>
      <th width="33.333%">Sopir penerima 2</td>
      <td width="66.666%"><?php echo $data['pindahan']['sj_2_nama_2']; ?></td>
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