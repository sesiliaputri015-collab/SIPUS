<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="page-header">
  <div class="page-icon" style="background:linear-gradient(135deg,#4527a0,#9c27b0)"><span class="material-icons">edit</span></div>
  <div><h1>Edit User</h1><p>Ubah data akun pengguna.</p></div>
  <div class="spacer"></div>
  <a href="<?= base_url('user') ?>" class="btn btn-secondary"><span class="material-icons">arrow_back</span> Kembali</a>
</div>

<div class="card" style="max-width:720px">
  <div class="card-header"><span class="material-icons" style="color:#4527a0">edit</span><h2>Edit User: <?= htmlspecialchars($u->nama) ?></h2></div>
  <div class="card-body">
    <form action="<?= base_url('user/update/'.$u->id_user) ?>" method="POST">

      <div class="form-row cols-2">
        <div class="form-group">
          <label>Nama Lengkap <span class="req">*</span></label>
          <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($u->nama) ?>" required>
        </div>
        <div class="form-group">
          <label>Username <span class="req">*</span></label>
          <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($u->username) ?>" required>
        </div>
      </div>

      <div class="form-row cols-2">
        <div class="form-group">
          <label>Password Baru</label>
          <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diubah">
          <div class="form-hint">Isi hanya jika ingin mengubah password.</div>
        </div>
        <div class="form-group">
          <label>Role <span class="req">*</span></label>
          <select name="role" class="form-control" required onchange="toggleAnggotaField(this.value)">
            <option value="admin"    <?= $u->role==='admin'    ?'selected':'' ?>>Admin</option>
            <option value="operator" <?= $u->role==='operator' ?'selected':'' ?>>Operator</option>
            <option value="anggota"  <?= $u->role==='anggota'  ?'selected':'' ?>>Anggota</option>
          </select>
        </div>
      </div>

      <div class="form-group" id="anggotaField" style="display:<?= $u->role==='anggota'?'block':'none' ?>">
        <label>Link ke Data Anggota</label>
        <select name="id_anggota" class="form-control">
          <option value="">— Tidak ditautkan —</option>
          <?php foreach ($anggota_list as $a): ?>
          <option value="<?= $a->id ?>" <?= $u->id_anggota == $a->id ?'selected':'' ?>><?= htmlspecialchars($a->nama) ?> (<?= $a->nim ?>)</option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label>Status</label>
        <select name="status" class="form-control">
          <option value="aktif"    <?= $u->status==='aktif'    ?'selected':'' ?>>Aktif</option>
          <option value="nonaktif" <?= $u->status==='nonaktif' ?'selected':'' ?>>Nonaktif</option>
        </select>
      </div>

      <div class="form-footer">
        <button type="submit" class="btn btn-primary"><span class="material-icons">save</span> Simpan Perubahan</button>
        <a href="<?= base_url('user') ?>" class="btn btn-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>
<script>
function toggleAnggotaField(role) {
  document.getElementById('anggotaField').style.display = role === 'anggota' ? 'block' : 'none';
}
</script>
