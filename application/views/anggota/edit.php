<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-header">
  <div class="page-icon" style="background:linear-gradient(135deg,#2e7d32,#4caf50)"><span class="material-icons">edit</span></div>
  <div><h1>Edit Anggota</h1></div>
  <div class="spacer"></div>
  <a href="<?= base_url('anggota') ?>" class="btn btn-secondary"><span class="material-icons">arrow_back</span> Kembali</a>
</div>
<div class="card" style="max-width:720px"><div class="card-header"><span class="material-icons" style="color:#2e7d32">edit</span><h2>Edit: <?= htmlspecialchars($anggota->nama) ?></h2></div>
<div class="card-body"><form action="<?= base_url('anggota/update/'.$anggota->id) ?>" method="POST">
  <div class="form-row cols-2">
    <div class="form-group"><label>Nama Lengkap <span class="req">*</span></label><input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($anggota->nama) ?>" required></div>
    <div class="form-group"><label>NIM <span class="req">*</span></label><input type="text" name="nim" class="form-control" value="<?= $anggota->nim ?>" required></div>
  </div>
  <div class="form-row cols-2">
    <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" value="<?= $anggota->email ?>"></div>
    <div class="form-group"><label>Program Studi</label><input type="text" name="prodi" class="form-control" value="<?= htmlspecialchars($anggota->prodi) ?>"></div>
  </div>
  <div class="form-row cols-2">
    <div class="form-group"><label>Angkatan</label><input type="number" name="angkatan" class="form-control" value="<?= $anggota->angkatan ?>"></div>
    <div class="form-group"><label>Status</label><select name="status" class="form-control"><option value="Aktif" <?= $anggota->status==='Aktif'?'selected':'' ?>>Aktif</option><option value="Tidak Aktif" <?= $anggota->status==='Tidak Aktif'?'selected':'' ?>>Tidak Aktif</option></select></div>
  </div>
  <div class="form-footer"><button type="submit" class="btn btn-primary"><span class="material-icons">save</span> Simpan Perubahan</button><a href="<?= base_url('anggota') ?>" class="btn btn-secondary">Batal</a></div>
</form></div></div>
