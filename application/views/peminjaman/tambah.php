<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-header">
  <div class="page-icon" style="background:linear-gradient(135deg,#e65100,#ff9800)"><span class="material-icons">post_add</span></div>
  <div><h1>Input Peminjaman Manual</h1><p>Untuk operator/admin langsung input tanpa antrian persetujuan.</p></div>
  <div class="spacer"></div>
  <a href="<?= base_url('peminjaman') ?>" class="btn btn-secondary"><span class="material-icons">arrow_back</span> Kembali</a>
</div>
<div class="card" style="max-width:720px"><div class="card-header"><span class="material-icons" style="color:#e65100">post_add</span><h2>Form Peminjaman Manual</h2></div>
<div class="card-body"><form action="<?= base_url('peminjaman/simpan') ?>" method="POST">
  <div class="form-group"><label>Anggota <span class="req">*</span></label>
    <select name="anggota_id" class="form-control" required>
      <option value="">— Pilih Anggota —</option>
      <?php foreach($anggota as $a): ?><option value="<?= $a->id ?>"><?= htmlspecialchars($a->nama) ?> — <?= $a->nim ?></option><?php endforeach; ?>
    </select>
  </div>
  <div class="form-group"><label>Buku <span class="req">*</span></label>
    <select name="buku_id" class="form-control" required>
      <option value="">— Pilih Buku —</option>
      <?php foreach($buku as $b): ?><option value="<?= $b->id ?>"><?= htmlspecialchars($b->judul) ?> (Tersedia: <?= $b->tersedia ?>)</option><?php endforeach; ?>
    </select>
  </div>
  <div class="form-row cols-2">
    <div class="form-group"><label>Tanggal Pinjam <span class="req">*</span></label><input type="date" name="tanggal_pinjam" class="form-control" value="<?= date('Y-m-d') ?>" required></div>
    <div class="form-group"><label>Tanggal Kembali <span class="req">*</span></label><input type="date" name="tanggal_kembali" class="form-control" value="<?= date('Y-m-d', strtotime('+7 days')) ?>" required></div>
  </div>
  <div class="form-group"><label>Catatan</label><textarea name="catatan" class="form-control" placeholder="Catatan opsional..."></textarea></div>
  <div class="form-footer"><button type="submit" class="btn btn-primary"><span class="material-icons">save</span> Simpan</button><a href="<?= base_url('peminjaman') ?>" class="btn btn-secondary">Batal</a></div>
</form></div></div>
