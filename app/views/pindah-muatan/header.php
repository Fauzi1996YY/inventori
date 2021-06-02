<div class="heading">
  <h1>Pindah Muatan</h1>
  <div class="actions">
    <a href="<?php echo BASE_URL . '/distribusi'; ?>" class="button secondary">Kembali ke daftar</a>
  </div>
</div>

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