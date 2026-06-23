<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="page-header">
  <div class="page-icon" style="background:linear-gradient(135deg,#4527a0,#9c27b0)"><span class="material-icons">manage_accounts</span></div>
  <div><h1>Manajemen User</h1><p>Kelola akun admin, operator, dan anggota sistem SIPUS.</p></div>
  <div class="spacer"></div>
  <a href="<?= base_url('user/tambah') ?>" class="btn btn-primary"><span class="material-icons">person_add</span> Tambah User</a>
</div>

<div class="card">
  <div class="card-body" style="padding:.875rem 1.25rem">
    <form method="GET" action="<?= base_url('user') ?>">
      <div class="search-bar">
        <div class="search-input-wrap">
          <span class="material-icons">search</span>
          <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Cari nama atau username...">
        </div>
        <select name="role" class="form-control" style="width:auto;padding:.5rem .875rem">
          <option value="">Semua Role</option>
          <option value="admin"    <?= ($filter_role??'')==='admin'    ?'selected':'' ?>>Admin</option>
          <option value="operator" <?= ($filter_role??'')==='operator' ?'selected':'' ?>>Operator</option>
          <option value="anggota"  <?= ($filter_role??'')==='anggota'  ?'selected':'' ?>>Anggota</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm"><span class="material-icons">search</span> Cari</button>
        <a href="<?= base_url('user') ?>" class="btn btn-secondary btn-sm"><span class="material-icons">refresh</span> Reset</a>
      </div>
    </form>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Nama</th>
          <th>Username</th>
          <th>Role</th>
          <th>Status</th>
          <th>Link Anggota</th>
          <th>Dibuat</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
      <?php if (empty($users)): ?>
      <tr><td colspan="8" class="empty-state">Tidak ada data user ditemukan</td></tr>
      <?php else: $no=1; foreach ($users as $u): ?>
      <tr>
        <td style="color:#94a3b8"><?= $no++ ?></td>
        <td>
          <div style="font-weight:600"><?= htmlspecialchars($u->nama) ?></div>
          <?php if ($u->id_user == ($this->session->userdata('id_user'))): ?>
          <div style="font-size:.7rem;color:#1976d2">(Anda)</div>
          <?php endif; ?>
        </td>
        <td><code style="background:#f1f5f9;padding:.1rem .4rem;border-radius:4px;font-size:.8125rem"><?= htmlspecialchars($u->username) ?></code></td>
        <td>
          <?php
          $role_badges = ['admin'=>'badge-admin','operator'=>'badge-operator','anggota'=>'badge-anggota'];
          $role_icons  = ['admin'=>'shield','operator'=>'manage_accounts','anggota'=>'person'];
          $rb = $role_badges[$u->role] ?? 'badge-anggota';
          $ri = $role_icons[$u->role]  ?? 'person';
          ?>
          <span class="badge <?= $rb ?>"><span class="material-icons" style="font-size:11px"><?= $ri ?></span><?= ucfirst($u->role) ?></span>
        </td>
        <td>
          <span class="badge <?= $u->status==='aktif' ? 'badge-aktif' : 'badge-nonaktif' ?>"><?= ucfirst($u->status) ?></span>
        </td>
        <td>
          <?php if ($u->id_anggota): ?>
          <span class="badge badge-dikembalikan"><span class="material-icons" style="font-size:11px">link</span>ID <?= $u->id_anggota ?></span>
          <?php else: ?>
          <span style="color:#94a3b8;font-size:.8rem">—</span>
          <?php endif; ?>
        </td>
        <td style="color:#94a3b8;font-size:.8rem"><?= date('d/m/Y', strtotime($u->created_at)) ?></td>
        <td>
          <a href="<?= base_url('user/edit/'.$u->id_user) ?>" class="btn btn-secondary btn-sm btn-icon" title="Edit"><span class="material-icons">edit</span></a>
          <?php if ($u->id_user != $this->session->userdata('id_user')): ?>
          <a href="<?= base_url('user/hapus/'.$u->id_user) ?>" class="btn btn-danger btn-sm btn-icon" title="Hapus" onclick="return confirm('Hapus user <?= addslashes($u->nama) ?>?')"><span class="material-icons">delete</span></a>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
