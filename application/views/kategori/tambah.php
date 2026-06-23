<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-header">
  <div class="page-icon" style="background:linear-gradient(135deg,#6a1b9a,#9c27b0)"><span class="material-icons">add</span></div>
  <div><h1>Tambah Kategori</h1></div>
  <div class="spacer"></div>
  <a href="<?= base_url('kategori') ?>" class="btn btn-secondary"><span class="material-icons">arrow_back</span> Kembali</a>
</div>
<div class="card" style="max-width:560px"><div class="card-header"><span class="material-icons" style="color:#9c27b0">add</span><h2>Form Tambah Kategori</h2></div>
<div class="card-body"><form action="<?= base_url('kategori/simpan') ?>" method="POST">
  <div class="form-group"><label>Nama Kategori <span class="req">*</span></label><input type="text" name="nama" class="form-control" placeholder="Contoh: Teknologi" required></div>
  <div class="form-group"><label>Deskripsi</label><textarea name="deskripsi" class="form-control" placeholder="Deskripsi singkat kategori..."></textarea></div>
  <div class="form-row cols-2">
    <div class="form-group"><label>Ikon (Material Icons)</label><input type="text" name="ikon" class="form-control" value="menu_book" placeholder="menu_book"><div class="form-hint">Nama ikon dari <a href="https://fonts.google.com/icons" target="_blank">Material Icons</a></div></div>
    <div class="form-group"><label>Warna</label><select name="warna" class="form-control"><option value="blue">Biru</option><option value="green">Hijau</option><option value="purple">Ungu</option><option value="teal">Teal</option><option value="amber">Amber</option><option value="red">Merah</option></select></div>
  </div>
  <div class="form-footer"><button type="submit" class="btn btn-primary"><span class="material-icons">save</span> Simpan</button><a href="<?= base_url('kategori') ?>" class="btn btn-secondary">Batal</a></div>
</form></div></div>
