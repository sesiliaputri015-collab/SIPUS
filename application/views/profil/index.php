<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-header">
  <div class="page-icon" style="background:linear-gradient(135deg,#37474f,#607d8b)"><span class="material-icons">manage_accounts</span></div>
  <div><h1>Profil Saya</h1><p>Kelola informasi akun dan password Anda.</p></div>
</div>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:1.25rem;max-width:900px" class="profil-grid">

  <!-- Kartu Info -->
  <div class="card" style="height:fit-content">
    <div style="padding:1.75rem;text-align:center">
      <div style="width:80px;height:80px;border-radius:50%;background:<?= $role==='admin'?'linear-gradient(135deg,#0f4c81,#1976d2)':($role==='operator'?'linear-gradient(135deg,#064e3b,#059669)':'linear-gradient(135deg,#312e81,#4f46e5)') ?>;margin:0 auto 1rem;display:flex;align-items:center;justify-content:center">
        <span class="material-icons" style="color:#fff;font-size:40px">account_circle</span>
      </div>
      <div style="font-size:1.1rem;font-weight:700;color:#1e293b"><?= htmlspecialchars($profil->nama) ?></div>
      <div style="font-size:.8125rem;color:#64748b;margin:.25rem 0"><?= htmlspecialchars($profil->username) ?></div>
      <?php
      $rb = ['admin'=>'badge-admin','operator'=>'badge-operator','anggota'=>'badge-anggota'][$role];
      $ri = ['admin'=>'shield','operator'=>'manage_accounts','anggota'=>'person'][$role];
      ?>
      <span class="badge <?= $rb ?>" style="margin-top:.5rem"><span class="material-icons" style="font-size:12px"><?= $ri ?></span><?= ucfirst($role) ?></span>
    </div>
    <?php if($anggota): ?>
    <div style="border-top:1px solid #f1f5f9;padding:1rem">
      <div style="font-size:.8rem;color:#64748b;margin-bottom:.5rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em">Data Anggota</div>
      <div style="font-size:.8375rem;margin-bottom:.375rem"><strong>NIM:</strong> <?= $anggota->nim ?></div>
      <div style="font-size:.8375rem;margin-bottom:.375rem"><strong>Prodi:</strong> <?= htmlspecialchars($anggota->prodi) ?></div>
      <div style="font-size:.8375rem"><strong>Email:</strong> <?= $anggota->email ?: '—' ?></div>
    </div>
    <?php endif; ?>
  </div>

  <!-- Form Edit -->
  <div class="card">
    <div class="card-header"><span class="material-icons" style="color:#607d8b">edit</span><h2>Edit Profil</h2></div>
    <div class="card-body">
      <form action="<?= base_url('profil/update') ?>" method="POST">

        <div class="form-group">
          <label>Nama Lengkap <span class="req">*</span></label>
          <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($profil->nama) ?>" required>
        </div>

        <?php if($anggota): ?>
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($anggota->email ?? '') ?>" placeholder="email@kampus.ac.id">
        </div>
        <?php endif; ?>

        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:1rem;margin-bottom:1rem">
          <div style="font-weight:600;font-size:.875rem;color:#374151;margin-bottom:.75rem">
            <span class="material-icons" style="font-size:16px;vertical-align:middle">lock</span> Ubah Password
          </div>
          <div class="form-row cols-2">
            <div class="form-group" style="margin-bottom:0">
              <label>Password Lama</label>
              <input type="password" name="password_lama" class="form-control" placeholder="Password saat ini">
            </div>
            <div class="form-group" style="margin-bottom:0">
              <label>Password Baru</label>
              <input type="password" name="password_baru" class="form-control" placeholder="Minimal 6 karakter">
            </div>
          </div>
          <div class="form-hint" style="margin-top:.5rem">Kosongkan jika tidak ingin mengubah password.</div>
        </div>

        <div class="form-footer">
          <button type="submit" class="btn btn-primary"><span class="material-icons">save</span> Simpan Perubahan</button>
          <a href="<?= base_url('dashboard') ?>" class="btn btn-secondary">Kembali</a>
        </div>
      </form>
    </div>
  </div>
</div>
