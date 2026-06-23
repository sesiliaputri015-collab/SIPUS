<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-header">
  <div class="page-icon" style="background:linear-gradient(135deg,#c62828,#ef5350)"><span class="material-icons">edit</span></div>
  <div><h1>Edit Denda</h1></div>
  <div class="spacer"></div>
  <a href="<?= base_url('denda') ?>" class="btn btn-secondary"><span class="material-icons">arrow_back</span> Kembali</a>
</div>
<div class="card" style="max-width:600px"><div class="card-header"><span class="material-icons" style="color:#c62828">edit</span><h2>Edit Denda — <?= htmlspecialchars($denda->nama_anggota) ?></h2></div>
<div class="card-body"><form action="<?= base_url('denda/update/'.$denda->id) ?>" method="POST">
  <div class="form-group"><label>Anggota</label>
    <select name="anggota_id" class="form-control" required>
      <?php foreach($anggota as $a): ?><option value="<?= $a->id ?>" <?= $denda->anggota_id==$a->id?'selected':'' ?>><?= htmlspecialchars($a->nama) ?> — <?= $a->nim ?></option><?php endforeach; ?>
    </select>
  </div>
  <div class="form-row cols-2">
    <div class="form-group"><label>Jumlah (Rp) <span class="req">*</span></label><input type="number" name="jumlah" class="form-control" value="<?= $denda->jumlah ?>" required></div>
    <div class="form-group"><label>Tanggal <span class="req">*</span></label><input type="date" name="tanggal" class="form-control" value="<?= $denda->tanggal ?>" required></div>
  </div>
  <div class="form-row cols-2">
    <div class="form-group"><label>Keterangan</label><input type="text" name="keterangan" class="form-control" value="<?= htmlspecialchars($denda->keterangan) ?>"></div>
    <div class="form-group"><label>Status</label>
      <select name="status" class="form-control">
        <option value="Belum Dibayar" <?= $denda->status==='Belum Dibayar'?'selected':'' ?>>Belum Dibayar</option>
        <option value="Lunas" <?= $denda->status==='Lunas'?'selected':'' ?>>Lunas</option>
      </select>
    </div>
  </div>
  <div class="form-footer"><button type="submit" class="btn btn-primary"><span class="material-icons">save</span> Simpan</button><a href="<?= base_url('denda') ?>" class="btn btn-secondary">Batal</a></div>
</form></div></div>
