<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-header">
  <div class="page-icon" style="background:linear-gradient(135deg,#6a1b9a,#9c27b0)"><span class="material-icons">category</span></div>
  <div><h1>Kategori Buku</h1><p><?= count($kategori) ?> kategori terdaftar</p></div>
  <div class="spacer"></div>
  <a href="<?= base_url('kategori/tambah') ?>" class="btn btn-primary"><span class="material-icons">add</span> Tambah Kategori</a>
</div>
<div class="card">
  <div class="table-wrap"><table>
    <thead><tr><th>#</th><th>Nama Kategori</th><th>Deskripsi</th><th>Jumlah Buku</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php if(empty($kategori)): ?>
    <tr><td colspan="5"><div class="empty-state"><span class="material-icons">category</span><p>Belum ada kategori</p></div></td></tr>
    <?php else: $no=1; foreach($kategori as $k): ?>
    <tr>
      <td style="color:#94a3b8"><?= $no++ ?></td>
      <td><div style="display:flex;align-items:center;gap:.5rem"><span class="material-icons" style="color:#9c27b0"><?= htmlspecialchars($k->ikon) ?></span><span style="font-weight:600"><?= htmlspecialchars($k->nama) ?></span></div></td>
      <td style="color:#64748b;font-size:.8375rem"><?= htmlspecialchars($k->deskripsi) ?: '—' ?></td>
      <td><span class="badge badge-dipinjam"><?= $this->Kategori_model->count_buku($k->nama) ?> buku</span></td>
      <td>
        <a href="<?= base_url('kategori/edit/'.$k->id) ?>" class="btn btn-secondary btn-sm btn-icon"><span class="material-icons">edit</span></a>
        <a href="<?= base_url('kategori/hapus/'.$k->id) ?>" class="btn btn-danger btn-sm btn-icon" onclick="return confirm('Hapus kategori ini?')"><span class="material-icons">delete</span></a>
      </td>
    </tr>
    <?php endforeach; endif; ?>
    </tbody>
  </table></div>
</div>
