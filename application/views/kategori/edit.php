<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-header">
  <div class="page-icon" style="background:linear-gradient(135deg,#6a1b9a,#9c27b0)"><span class="material-icons">edit</span></div>
  <div><h1>Edit Kategori</h1></div>
  <div class="spacer"></div>
  <a href="<?= base_url('kategori') ?>" class="btn btn-secondary"><span class="material-icons">arrow_back</span> Kembali</a>
</div>
<div class="card" style="max-width:560px"><div class="card-header"><span class="material-icons" style="color:#9c27b0">edit</span><h2>Edit: <?= htmlspecialchars($kategori->nama) ?></h2></div>
<div class="card-body"><form action="<?= base_url('kategori/update/'.$kategori->id) ?>" method="POST">
  <div class="form-group"><label>Nama Kategori <span class="req">*</span></label><input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($kategori->nama) ?>" required></div>
  <div class="form-group"><label>Deskripsi</label><textarea name="deskripsi" class="form-control"><?= htmlspecialchars($kategori->deskripsi) ?></textarea></div>
  <div class="form-row cols-2">
    <div class="form-group"><label>Ikon</label><input type="text" name="ikon" class="form-control" value="<?= htmlspecialchars($kategori->ikon) ?>"></div>
    <div class="form-group"><label>Warna</label><select name="warna" class="form-control">
      <?php foreach(['blue','green','purple','teal','amber','red'] as $w): ?>
      <option value="<?= $w ?>" <?= $kategori->warna===$w?'selected':'' ?>><?= ucfirst($w) ?></option>
      <?php endforeach; ?>
    </select></div>
  </div>
  <div class="form-footer"><button type="submit" class="btn btn-primary"><span class="material-icons">save</span> Simpan Perubahan</button><a href="<?= base_url('kategori') ?>" class="btn btn-secondary">Batal</a></div>
</form></div></div>
