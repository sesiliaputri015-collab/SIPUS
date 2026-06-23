<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="page-header">
  <div class="page-icon" style="background:linear-gradient(135deg,#312e81,#4f46e5)"><span class="material-icons">home</span></div>
  <div>
    <h1>Selamat Datang, <?= htmlspecialchars($user->nama) ?>!</h1>
    <p><?= $anggota ? 'NIM: '.$anggota->nim.' &mdash; '.$anggota->prodi : 'Akun Anggota Perpustakaan' ?></p>
  </div>
  <div class="spacer"></div>
  <a href="<?= base_url('peminjaman/ajukan') ?>" class="btn btn-primary">
    <span class="material-icons">add_circle</span> Pinjam Buku
  </a>
</div>

<div class="stats-grid cols-4">
  <div class="stat-card indigo"><div class="stat-icon indigo"><span class="material-icons">pending_actions</span></div><div class="stat-info"><div class="value"><?= count($menunggu) ?></div><div class="label">Menunggu Disetujui</div></div></div>
  <div class="stat-card blue"><div class="stat-icon blue"><span class="material-icons">swap_horiz</span></div><div class="stat-info"><div class="value"><?= count($pinjam_aktif) ?></div><div class="label">Sedang Dipinjam</div></div></div>
  <div class="stat-card red"><div class="stat-icon red"><span class="material-icons">alarm</span></div><div class="stat-info"><div class="value"><?= count($terlambat) ?></div><div class="label">Terlambat Kembali</div></div></div>
  <div class="stat-card amber"><div class="stat-icon amber"><span class="material-icons">receipt_long</span></div><div class="stat-info"><div class="value" style="font-size:1.1rem">Rp<?= number_format($total_denda,0,',','.') ?></div><div class="label">Denda Belum Bayar</div></div></div>
</div>

<div class="card" style="margin-bottom:1rem">
  <div class="card-header">
    <span class="material-icons" style="color:#1d4ed8">book</span>
    <h2>Buku Sedang Dipinjam</h2>
    <a href="<?= base_url('peminjaman') ?>" class="btn btn-secondary btn-sm" style="margin-left:auto">Lihat Semua</a>
  </div>
  <?php if (empty($pinjam_aktif) && empty($menunggu) && empty($terlambat)): ?>
  <div class="empty-state">
    <span class="material-icons">import_contacts</span>
    <p>Belum ada peminjaman aktif</p>
    <a href="<?= base_url('peminjaman/ajukan') ?>" class="btn btn-primary" style="margin-top:.75rem"><span class="material-icons">add_circle</span> Pinjam Buku Sekarang</a>
  </div>
  <?php else: ?>
  <div class="table-wrap">
    <table>
      <thead><tr><th>Buku</th><th>Penulis</th><th>Tgl Kembali</th><th>Status</th><th>Aksi</th></tr></thead>
      <tbody>
      <?php
      $all_aktif = array_merge($menunggu, $pinjam_aktif, $terlambat);
      foreach ($all_aktif as $p):
        $terlambat_flag = $p->status === 'Terlambat';
        $hari_sisa = (strtotime($p->tanggal_kembali) - time()) / 86400;
      ?>
      <tr>
        <td><div style="font-weight:600"><?= htmlspecialchars($p->judul_buku) ?></div></td>
        <td><?= htmlspecialchars($p->penulis) ?></td>
        <td style="<?= $terlambat_flag ? 'color:#dc2626;font-weight:600' : '' ?>">
          <?= date('d M Y', strtotime($p->tanggal_kembali)) ?>
          <?php if (!$terlambat_flag && $hari_sisa > 0 && $hari_sisa <= 3): ?>
          <div style="font-size:.7rem;color:#d97706">Segera (<?= ceil($hari_sisa) ?> hari)</div>
          <?php elseif ($terlambat_flag): ?>
          <div style="font-size:.7rem;color:#dc2626">Terlambat!</div>
          <?php endif; ?>
        </td>
        <td>
          <?php
          $badge_map = ['Menunggu'=>'badge-menunggu','Dipinjam'=>'badge-dipinjam','Terlambat'=>'badge-terlambat'];
          $bc = $badge_map[$p->status] ?? 'badge-menunggu';
          ?>
          <span class="badge <?= $bc ?>"><?= $p->status ?></span>
        </td>
        <td>
          <?php if (in_array($p->status, ['Dipinjam','Terlambat'])): ?>
          <a href="<?= base_url('peminjaman/ajukan_kembali/'.$p->id) ?>" class="btn btn-warning btn-sm" onclick="return confirm('Ajukan pengembalian buku ini?')">
            <span class="material-icons">assignment_return</span> Kembalikan
          </a>
          <?php else: ?>
          <span style="font-size:.75rem;color:#94a3b8">Menunggu operator</span>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>

<?php if (!empty($denda_aktif)): ?>
<div class="card" style="margin-bottom:1rem">
  <div class="card-header">
    <span class="material-icons" style="color:#dc2626">receipt_long</span>
    <h2>Denda Belum Dibayar</h2>
    <span class="badge badge-terlambat">Rp<?= number_format($total_denda,0,',','.') ?></span>
  </div>
  <div class="table-wrap">
    <table>
      <thead><tr><th>Tanggal</th><th>Keterangan</th><th>Jumlah</th><th>Status</th></tr></thead>
      <tbody>
      <?php foreach ($denda_aktif as $d): if ($d->status !== 'Belum Dibayar') continue; ?>
      <tr>
        <td><?= date('d M Y', strtotime($d->tanggal)) ?></td>
        <td><?= htmlspecialchars($d->keterangan) ?></td>
        <td style="font-weight:700;color:#dc2626">Rp<?= number_format($d->jumlah,0,',','.') ?></td>
        <td><span class="badge badge-belum">Belum Dibayar</span></td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div class="card-body" style="padding:.75rem 1.25rem;background:#fef2f2;font-size:.8125rem;color:#dc2626">
    <span class="material-icons" style="font-size:16px;vertical-align:middle">info</span>
    Silakan datang ke perpustakaan untuk melunasi denda Anda.
  </div>
</div>
<?php endif; ?>

<div class="card">
  <div class="card-header">
    <span class="material-icons" style="color:#2e7d32">import_contacts</span>
    <h2>Katalog Buku Tersedia</h2>
    <a href="<?= base_url('peminjaman/ajukan') ?>" class="btn btn-primary btn-sm" style="margin-left:auto"><span class="material-icons">add_circle</span> Pinjam</a>
  </div>
  <div class="table-wrap">
    <table>
      <thead><tr><th>Judul</th><th>Penulis</th><th>Kategori</th><th>Tersedia</th><th></th></tr></thead>
      <tbody>
      <?php foreach (array_slice($katalog,0,8) as $b): ?>
      <tr>
        <td style="font-weight:500"><?= htmlspecialchars($b->judul) ?></td>
        <td><?= htmlspecialchars($b->penulis) ?></td>
        <td><span class="badge badge-dipinjam"><?= htmlspecialchars($b->kategori) ?></span></td>
        <td><span class="badge badge-dikembalikan"><?= $b->tersedia ?> buku</span></td>
        <td><a href="<?= base_url('peminjaman/kirim_ajuan/'.$b->id) ?>" class="btn btn-primary btn-sm" onclick="return confirm('Ajukan peminjaman buku: <?= addslashes($b->judul) ?>?')"><span class="material-icons">add_circle</span></a></td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php if (count($katalog) > 8): ?>
  <div style="padding:.75rem 1.25rem;border-top:1px solid #f1f5f9">
    <a href="<?= base_url('peminjaman/ajukan') ?>" class="btn btn-secondary btn-sm">Lihat semua <?= count($katalog) ?> buku tersedia</a>
  </div>
  <?php endif; ?>
</div>
