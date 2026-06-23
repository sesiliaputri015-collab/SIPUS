<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-header">
  <div class="page-icon" style="background:linear-gradient(135deg,#1565c0,#1976d2)"><span class="material-icons">edit</span></div>
  <div><h1>Edit Buku</h1></div>
  <div class="spacer"></div>
  <a href="<?= base_url('buku') ?>" class="btn btn-secondary"><span class="material-icons">arrow_back</span> Kembali</a>
</div>
<div class="card" style="max-width:720px"><div class="card-header"><span class="material-icons" style="color:#1976d2">edit</span><h2>Edit: <?= htmlspecialchars($buku->judul) ?></h2></div>
<div class="card-body"><form action="<?= base_url('buku/update/'.$buku->id) ?>" method="POST">
  <div class="form-group"><label>Judul Buku <span class="req">*</span></label><input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($buku->judul) ?>" required></div>
  <div class="form-row cols-2">
    <div class="form-group"><label>Penulis <span class="req">*</span></label><input type="text" name="penulis" class="form-control" value="<?= htmlspecialchars($buku->penulis) ?>" required></div>
    <div class="form-group"><label>ISBN</label><input type="text" name="isbn" class="form-control" value="<?= htmlspecialchars($buku->isbn) ?>"></div>
  </div>
  <div class="form-row cols-3">
    <div class="form-group"><label>Kategori</label><select name="kategori" class="form-control"><option value="">— Pilih —</option><?php foreach($kategori_list as $k): ?><option value="<?= $k ?>" <?= $buku->kategori==$k?'selected':'' ?>><?= htmlspecialchars($k) ?></option><?php endforeach; ?></select></div>
    <div class="form-group"><label>Tahun Terbit</label><input type="number" name="tahun" class="form-control" value="<?= $buku->tahun ?>"></div>
    <div class="form-group"><label>Jumlah Stok <span class="req">*</span></label><input type="number" name="stok" class="form-control" value="<?= $buku->stok ?>" min="0" required><div class="form-hint">Stok tersedia akan diperbarui otomatis.</div></div>
  </div>
  <div class="form-footer"><button type="submit" class="btn btn-primary"><span class="material-icons">save</span> Simpan Perubahan</button><a href="<?= base_url('buku') ?>" class="btn btn-secondary">Batal</a></div>
</form></div></div>
