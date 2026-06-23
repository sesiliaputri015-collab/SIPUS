<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-header">
  <div class="page-icon" style="background:linear-gradient(135deg,#1565c0,#1976d2)"><span class="material-icons">add_box</span></div>
  <div><h1>Tambah Buku</h1></div>
  <div class="spacer"></div>
  <a href="<?= base_url('buku') ?>" class="btn btn-secondary"><span class="material-icons">arrow_back</span> Kembali</a>
</div>
<div class="card" style="max-width:720px"><div class="card-header"><span class="material-icons" style="color:#1976d2">add_box</span><h2>Form Tambah Buku</h2></div>
<div class="card-body"><form action="<?= base_url('buku/simpan') ?>" method="POST">
  <div class="form-group"><label>Judul Buku <span class="req">*</span></label><input type="text" name="judul" class="form-control" placeholder="Judul buku" required></div>
  <div class="form-row cols-2">
    <div class="form-group"><label>Penulis <span class="req">*</span></label><input type="text" name="penulis" class="form-control" placeholder="Nama penulis" required></div>
    <div class="form-group"><label>ISBN</label><input type="text" name="isbn" class="form-control" placeholder="978-xxx-xxx-xxx-x"></div>
  </div>
  <div class="form-row cols-3">
    <div class="form-group"><label>Kategori</label><select name="kategori" class="form-control"><option value="">— Pilih —</option><?php foreach($kategori_list as $k): ?><option value="<?= $k ?>"><?= htmlspecialchars($k) ?></option><?php endforeach; ?></select></div>
    <div class="form-group"><label>Tahun Terbit</label><input type="number" name="tahun" class="form-control" placeholder="<?= date('Y') ?>" min="1900" max="<?= date('Y') ?>"></div>
    <div class="form-group"><label>Jumlah Stok <span class="req">*</span></label><input type="number" name="stok" class="form-control" value="1" min="0" required></div>
  </div>
  <div class="form-footer"><button type="submit" class="btn btn-primary"><span class="material-icons">save</span> Simpan</button><a href="<?= base_url('buku') ?>" class="btn btn-secondary">Batal</a></div>
</form></div></div>
