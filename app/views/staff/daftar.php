<div class="heading">
  <h1>Daftar Staff</h1>
  <div class="actions">
    <a href="<?php echo BASE_URL;?>/staff/form" class="button secondary">+ Buat staff baru</a>
  </div>
</div>

<!-- Flasher -->
<?php \App\Core\Flasher::show('staff-daftar'); ?>

<table class="resp">
  <thead>
    <tr>
      <th width="0%">Username</th>
      <th width="0%">Nama</th>
      <!-- <th widht="0%">password</th> -->
      <th width="0%"></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($data['staff'] as $k => $v): ?>
      <tr>
        <td data-label="Email" class="nowrap"><?php echo $v['email']; ?></td>
        <td data-label="Nama" class="nowrap"><?php echo $v['nama']; ?></td>
        <!-- <td data-label="Password" class="nowrap"><?php echo $v['password']; ?></td> -->
        <td>
          <div class="actions">
            <a href="<?php echo BASE_URL . '/staff/form/' . $v['id_user'];?>"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#edit"></use></svg></a>
            <a href="<?php echo BASE_URL . '/staff/hapus/' . $v['id_user'];?>" class="dangerous"><svg><use xlink:href="<?php echo BASE_URL; ?>/assets/images/sprite.svg#delete"></use></svg></a>
          </div>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- Pagination -->
<?php echo isset($data['paging']) ? $data['paging'] : ''; ?>