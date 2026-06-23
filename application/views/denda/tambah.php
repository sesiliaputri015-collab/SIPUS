<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-header">
  <div class="page-icon" style="background:linear-gradient(135deg,#c62828,#ef5350)"><span class="material-icons">add</span></div>
  <div><h1>Tambah Denda</h1></div>
  <div class="spacer"></div>
  <a href="<?= base_url('denda') ?>" class="btn btn-secondary"><span class="material-icons">arrow_back</span> Kembali</a>
</div>
<div class="card" style="max-width:600px"><div class="card-header"><span class="material-icons" style="color:#c62828">receipt_long</span><h2>Form Tambah Denda</h2></div>
<div class="card-body"><form action="<?= base_url('denda/simpan') ?>" method="POST">
  <div class="form-group"><label>Anggota <span class="req">*</span></label>
    <select name="anggota_id" class="form-control" required>
      <option value="">— Pilih Anggota —</option>
      <?php foreach($anggota as $a): ?><option value="<?= $a->id ?>"><?= htmlspecialchars($a->nama) ?> — <?= $a->nim ?></option><?php endforeach; ?>
    </select>
  </div>
  <div class="form-row cols-2">
    <div class="form-group"><label>Jumlah Denda (Rp) <span class="req">*</span></label><input type="number" name="jumlah" class="form-control" placeholder="5000" min="1" required></div>
    <div class="form-group"><label>Tanggal <span class="req">*</span></label><input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required></div>
  </div>
  <div class="form-group"><label>Keterangan</label><input type="text" name="keterangan" class="form-control" placeholder="Contoh: Terlambat 5 hari — Buku: Judul Buku"></div>
  <div class="form-footer"><button type="submit" class="btn btn-primary"><span class="material-icons">save</span> Simpan</button><a href="<?= base_url('denda') ?>" class="btn btn-secondary">Batal</a></div>
</form></div></div>
