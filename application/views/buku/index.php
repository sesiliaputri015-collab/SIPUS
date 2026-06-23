<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-header">
  <div class="page-icon" style="background:linear-gradient(135deg,#1565c0,#1976d2)"><span class="material-icons">menu_book</span></div>
  <div><h1><?= $role==='anggota'?'Katalog Buku':'Data Buku' ?></h1><p><?= number_format(count($buku)) ?> buku ditemukan</p></div>
  <div class="spacer"></div>
  <?php if (in_array($role,['admin','operator'])): ?>
  <a href="<?= base_url('buku/tambah') ?>" class="btn btn-primary"><span class="material-icons">add</span> Tambah Buku</a>
  <?php endif; ?>
</div>
<div class="card">
  <div class="card-body" style="padding:.875rem 1.25rem">
    <form method="GET" action="<?= base_url('buku') ?>"><div class="search-bar">
      <div class="search-input-wrap"><span class="material-icons">search</span>
        <input type="text" name="search" value="<?= htmlspecialchars($search??'') ?>" placeholder="Cari judul, penulis, atau kategori...">
      </div>
      <button type="submit" class="btn btn-primary btn-sm"><span class="material-icons">search</span> Cari</button>
      <a href="<?= base_url('buku') ?>" class="btn btn-secondary btn-sm"><span class="material-icons">refresh</span></a>
    </div></form>
  </div>
  <div class="table-wrap"><table>
    <thead><tr><th>#</th><th>Judul</th><th>Penulis</th><th>Kategori</th><th>Tahun</th><th>Stok</th><th>Tersedia</th><?php if (in_array($role,['admin','operator'])): ?><th>Aksi</th><?php endif; ?></tr></thead>
    <tbody>
    <?php if(empty($buku)): ?><tr><td colspan="8"><div class="empty-state"><span class="material-icons">search_off</span><p>Tidak ada buku ditemukan</p></div></td></tr>
    <?php else: $no=1; foreach($buku as $b): ?>
    <tr>
      <td style="color:#94a3b8"><?= $no++ ?></td>
      <td><div style="font-weight:600"><?= htmlspecialchars($b->judul) ?></div><?php if($b->isbn): ?><div style="font-size:.7rem;color:#94a3b8">ISBN: <?= $b->isbn ?></div><?php endif; ?></td>
      <td><?= htmlspecialchars($b->penulis) ?></td>
      <td><?php if($b->kategori): ?><span class="badge badge-dipinjam"><?= htmlspecialchars($b->kategori) ?></span><?php else: ?>—<?php endif; ?></td>
      <td><?= $b->tahun ?: '—' ?></td>
      <td><?= $b->stok ?></td>
      <td><span class="badge <?= $b->tersedia>0?'badge-dikembalikan':'badge-terlambat' ?>"><?= $b->tersedia ?></span></td>
      <?php if(in_array($role,['admin','operator'])): ?>
      <td>
        <a href="<?= base_url('buku/edit/'.$b->id) ?>" class="btn btn-secondary btn-sm btn-icon" title="Edit"><span class="material-icons">edit</span></a>
        <a href="<?= base_url('buku/hapus/'.$b->id) ?>" class="btn btn-danger btn-sm btn-icon" title="Hapus" onclick="return confirm('Hapus buku ini?')"><span class="material-icons">delete</span></a>
      </td>
      <?php elseif($role==='anggota'): ?>
      <?php endif; ?>
    </tr>
    <?php endforeach; endif; ?>
    </tbody>
  </table></div>
</div>
