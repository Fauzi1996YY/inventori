<div class="heading">
  <h1>Daftar Peminjaman</h1>
  <div class="actions">
    <a href="<?php echo BASE_URL;?>/peminjaman/form" class="button secondary">+ Buat peminjaman baru</a>
  </div>
</div>

<!-- Flasher -->
<?php \App\Core\Flasher::show('peminjaman-daftar'); ?>

<table class="resp">
  <thead>
    <tr>
      <th width="20%">Kode barang</th>
      <th width="20%">Nama barang</th>
      <th width="20%">Nama anggota</th>
      <th width="0%" class="align-right">Jumlah</th>
      <th width="20%">Tanggal peminjaman</th>
      <th width="20%">Tanggal pengembalian</th>
      <th width="0%"></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($data['peminjaman'] as $k => $v): ?>
      <tr>
        <td data-label="Kode barang" class="nowrap"><?php echo $v['kode_barang']; ?></td>
        <td data-label="Nama barang" class="nowrap"><?php echo $v['nama_barang']; ?></td>
        <td data-label="Nama anggota"><?php echo $v['nama_anggota']; ?></td>
        <td data-label="Jumlah" class="align-right"><?php echo $v['jumlah']; ?></td>
        <td data-label="Tanggal peminjaman"><?php echo $v['tgl_peminjaman']; ?></td>
        <td data-label="Tanggal pengembalian"><?php echo $v['tgl_pengembalian']; ?></td>
        <td>
          <div class="actions">
            <a href="<?php echo BASE_URL . '/peminjaman/form/' . $v['id_peminjaman'];?>"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#edit"></use></svg></a>
            <a href="<?php echo BASE_URL . '/peminjaman/hapus/' . $v['id_peminjaman'];?>" class="dangerous"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#delete"></use></svg></a>
          </div>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- Pagination -->
<?php echo isset($data['paging']) ? $data['paging'] : ''; ?>