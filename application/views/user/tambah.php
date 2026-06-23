<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="page-header">
  <div class="page-icon" style="background:linear-gradient(135deg,#4527a0,#9c27b0)"><span class="material-icons">person_add</span></div>
  <div><h1>Tambah User Baru</h1><p>Buat akun pengguna baru dengan role yang sesuai.</p></div>
  <div class="spacer"></div>
  <a href="<?= base_url('user') ?>" class="btn btn-secondary"><span class="material-icons">arrow_back</span> Kembali</a>
</div>

<div class="card" style="max-width:720px">
  <div class="card-header"><span class="material-icons" style="color:#4527a0">person_add</span><h2>Form Tambah User</h2></div>
  <div class="card-body">
    <form action="<?= base_url('user/simpan') ?>" method="POST">

      <div class="form-row cols-2">
        <div class="form-group">
          <label>Nama Lengkap <span class="req">*</span></label>
          <input type="text" name="nama" class="form-control" placeholder="Nama lengkap" required>
        </div>
        <div class="form-group">
          <label>Username <span class="req">*</span></label>
          <input type="text" name="username" class="form-control" placeholder="Tanpa spasi" required>
        </div>
      </div>

      <div class="form-row cols-2">
        <div class="form-group">
          <label>Password <span class="req">*</span></label>
          <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
          <div class="form-hint">Password akan di-hash (bcrypt) secara otomatis.</div>
        </div>
        <div class="form-group">
          <label>Role <span class="req">*</span></label>
          <select name="role" class="form-control" required id="roleSelect" onchange="toggleAnggotaField(this.value)">
            <option value="">— Pilih Role —</option>
            <option value="admin">Admin — Akses penuh sistem</option>
            <option value="operator">Operator — Kelola buku & peminjaman</option>
            <option value="anggota">Anggota — Peminjam buku</option>
          </select>
        </div>
      </div>

      <div class="form-group" id="anggotaField" style="display:none">
        <label>Link ke Data Anggota</label>
        <select name="id_anggota" class="form-control">
          <option value="">— Tidak ditautkan —</option>
          <?php foreach ($anggota_list as $a): ?>
          <option value="<?= $a->id ?>"><?= htmlspecialchars($a->nama) ?> (<?= $a->nim ?>)</option>
          <?php endforeach; ?>
        </select>
        <div class="form-hint">Tautkan ke data anggota agar bisa meminjam buku.</div>
      </div>

      <div class="form-group">
        <label>Status</label>
        <select name="status" class="form-control">
          <option value="aktif">Aktif</option>
          <option value="nonaktif">Nonaktif</option>
        </select>
      </div>

      <!-- Info perbedaan role — responsive -->
      <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:1rem;margin-bottom:1rem;font-size:.8125rem">
        <div style="font-weight:600;color:#374151;margin-bottom:.75rem"><span class="material-icons" style="font-size:16px;vertical-align:middle;color:#1976d2">info</span> Perbedaan Role</div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:.75rem" class="role-info-grid">
          <div>
            <span class="badge badge-admin" style="margin-bottom:.35rem">Admin</span>
            <ul style="padding-left:1rem;color:#64748b;line-height:1.8;margin-top:.35rem">
              <li>CRUD semua data</li><li>Kelola user & role</li><li>Kelola kategori</li><li>Dashboard penuh</li>
            </ul>
          </div>
          <div>
            <span class="badge badge-operator" style="margin-bottom:.35rem">Operator</span>
            <ul style="padding-left:1rem;color:#64748b;line-height:1.8;margin-top:.35rem">
              <li>CRUD buku & anggota</li><li>Setujui peminjaman</li><li>Verif. pengembalian</li><li>Tidak kelola user</li>
            </ul>
          </div>
          <div>
            <span class="badge badge-anggota" style="margin-bottom:.35rem">Anggota</span>
            <ul style="padding-left:1rem;color:#64748b;line-height:1.8;margin-top:.35rem">
              <li>Lihat katalog buku</li><li>Ajukan peminjaman</li><li>Ajukan pengembalian</li><li>Riwayat & denda</li>
            </ul>
          </div>
        </div>
      </div>

      <div class="form-footer">
        <button type="submit" class="btn btn-primary"><span class="material-icons">save</span> Simpan User</button>
        <a href="<?= base_url('user') ?>" class="btn btn-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>

<style>
@media(max-width:600px){
  .role-info-grid{grid-template-columns:1fr!important}
}
</style>
<script>
function toggleAnggotaField(role) {
  document.getElementById('anggotaField').style.display = role === 'anggota' ? 'block' : 'none';
}
</script>
