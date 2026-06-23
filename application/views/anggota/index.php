<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-header">
  <div class="page-icon" style="background:linear-gradient(135deg,#2e7d32,#4caf50)"><span class="material-icons">groups</span></div>
  <div><h1>Data Anggota</h1><p><?= count($anggota) ?> anggota terdaftar</p></div>
  <div class="spacer"></div>
  <a href="<?= base_url('anggota/tambah') ?>" class="btn btn-primary"><span class="material-icons">group_add</span> Tambah Anggota</a>
</div>
<div class="card">
  <div class="card-body" style="padding:.875rem 1.25rem">
    <form method="GET" action="<?= base_url('anggota') ?>"><div class="search-bar">
      <div class="search-input-wrap"><span class="material-icons">search</span>
        <input type="text" name="search" value="<?= htmlspecialchars($search??'') ?>" placeholder="Cari nama, NIM, atau prodi...">
      </div>
      <button type="submit" class="btn btn-primary btn-sm"><span class="material-icons">search</span> Cari</button>
      <a href="<?= base_url('anggota') ?>" class="btn btn-secondary btn-sm"><span class="material-icons">refresh</span></a>
    </div></form>
  </div>
  <div class="table-wrap"><table>
    <thead><tr><th>#</th><th>Nama</th><th>NIM</th><th>Prodi</th><th>Angkatan</th><th>Email</th><th>Status</th><th>Total Pinjam</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php if(empty($anggota)): ?><tr><td colspan="9"><div class="empty-state"><span class="material-icons">people_outline</span><p>Belum ada data anggota</p></div></td></tr>
    <?php else: $no=1; foreach($anggota as $a): ?>
    <tr>
      <td style="color:#94a3b8"><?= $no++ ?></td>
      <td style="font-weight:600"><?= htmlspecialchars($a->nama) ?></td>
      <td><code style="background:#f1f5f9;padding:.1rem .4rem;border-radius:4px;font-size:.8rem"><?= $a->nim ?></code></td>
      <td><?= htmlspecialchars($a->prodi) ?></td>
      <td><?= $a->angkatan ?: '—' ?></td>
      <td style="font-size:.8125rem"><?= $a->email ?: '—' ?></td>
      <td><span class="badge <?= $a->status==='Aktif'?'badge-aktif':'badge-nonaktif' ?>"><?= $a->status ?></span></td>
      <td style="text-align:center"><span class="badge badge-dipinjam"><?= $a->total_pinjam ?>×</span></td>
      <td>
        <a href="<?= base_url('anggota/edit/'.$a->id) ?>" class="btn btn-secondary btn-sm btn-icon"><span class="material-icons">edit</span></a>
        <?php if($role==='admin'): ?>
        <a href="<?= base_url('anggota/hapus/'.$a->id) ?>" class="btn btn-danger btn-sm btn-icon" onclick="return confirm('Hapus anggota ini?')"><span class="material-icons">delete</span></a>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; endif; ?>
    </tbody>
  </table></div>
</div>
