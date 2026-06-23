<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="page-header">
  <div class="page-icon" style="background:linear-gradient(135deg,#064e3b,#059669)"><span class="material-icons">dashboard</span></div>
  <div><h1>Dashboard Operator</h1><p>Selamat datang, <?= htmlspecialchars($user->nama) ?>. Kelola antrian peminjaman dan pengembalian buku.</p></div>
</div>

<div class="stats-grid cols-4">
  <div class="stat-card orange"><div class="stat-icon orange"><span class="material-icons">pending_actions</span></div><div class="stat-info"><div class="value"><?= $pinjam_menunggu ?></div><div class="label">Menunggu Disetujui</div></div></div>
  <div class="stat-card blue"><div class="stat-icon indigo"><span class="material-icons">swap_horiz</span></div><div class="stat-info"><div class="value"><?= $pinjam_aktif ?></div><div class="label">Sedang Dipinjam</div></div></div>
  <div class="stat-card teal"><div class="stat-icon teal"><span class="material-icons">assignment_return</span></div><div class="stat-info"><div class="value"><?= $pinjam_menunggu_kembali ?></div><div class="label">Menunggu Kembali</div></div></div>
  <div class="stat-card red"><div class="stat-icon red"><span class="material-icons">alarm</span></div><div class="stat-info"><div class="value"><?= $pinjam_terlambat ?></div><div class="label">Terlambat</div></div></div>
</div>
<div class="stats-grid cols-3">
  <div class="stat-card green"><div class="stat-icon green"><span class="material-icons">menu_book</span></div><div class="stat-info"><div class="value"><?= $buku_tersedia ?></div><div class="label">Buku Tersedia</div></div></div>
  <div class="stat-card purple"><div class="stat-icon purple"><span class="material-icons">groups</span></div><div class="stat-info"><div class="value"><?= $anggota_aktif ?></div><div class="label">Anggota Aktif</div></div></div>
  <div class="stat-card amber"><div class="stat-icon amber"><span class="material-icons">receipt_long</span></div><div class="stat-info"><div class="value" style="font-size:1.1rem">Rp<?= number_format($total_denda,0,',','.') ?></div><div class="label">Total Denda Belum Bayar</div></div></div>
</div>

<div class="card" style="margin-bottom:1rem">
  <div class="card-header">
    <span class="material-icons" style="color:#d97706">pending_actions</span>
    <h2>Antrian Persetujuan Peminjaman</h2>
    <?php if ($pinjam_menunggu > 0): ?><span class="badge badge-terlambat"><?= $pinjam_menunggu ?> menunggu</span><?php endif; ?>
    <a href="<?= base_url('peminjaman?status=menunggu') ?>" class="btn btn-secondary btn-sm" style="margin-left:auto">Lihat Semua</a>
  </div>
  <?php if (empty($antrian_pinjam)): ?>
  <div class="empty-state"><span class="material-icons">check_circle_outline</span><p>Tidak ada antrian peminjaman</p></div>
  <?php else: ?>
  <div class="table-wrap">
    <table>
      <thead><tr><th>Anggota</th><th>Buku</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Aksi</th></tr></thead>
      <tbody>
      <?php foreach ($antrian_pinjam as $p): ?>
      <tr>
        <td><div style="font-weight:600"><?= htmlspecialchars($p->nama_anggota) ?></div><div style="font-size:.75rem;color:#94a3b8"><?= $p->nim ?></div></td>
        <td><?= htmlspecialchars($p->judul_buku) ?></td>
        <td><?= date('d/m/Y', strtotime($p->tanggal_pinjam)) ?></td>
        <td><?= date('d/m/Y', strtotime($p->tanggal_kembali)) ?></td>
        <td style="white-space:nowrap">
          <a href="<?= base_url('peminjaman/setujui/'.$p->id) ?>" class="btn btn-success btn-sm" onclick="return confirm('Setujui peminjaman ini?')"><span class="material-icons">check</span> Setujui</a>
          <a href="<?= base_url('peminjaman/tolak/'.$p->id) ?>" class="btn btn-danger btn-sm btn-icon" onclick="return confirm('Tolak?')"><span class="material-icons">close</span></a>
        </td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>

<div class="card" style="margin-bottom:1rem">
  <div class="card-header">
    <span class="material-icons" style="color:#1d4ed8">assignment_return</span>
    <h2>Antrian Verifikasi Pengembalian</h2>
    <?php if ($pinjam_menunggu_kembali > 0): ?><span class="badge badge-dipinjam"><?= $pinjam_menunggu_kembali ?> menunggu</span><?php endif; ?>
    <a href="<?= base_url('peminjaman?status=menunggu_kembali') ?>" class="btn btn-secondary btn-sm" style="margin-left:auto">Lihat Semua</a>
  </div>
  <?php if (empty($antrian_kembali)): ?>
  <div class="empty-state"><span class="material-icons">check_circle_outline</span><p>Tidak ada pengembalian yang menunggu verifikasi</p></div>
  <?php else: ?>
  <div class="table-wrap">
    <table>
      <thead><tr><th>Anggota</th><th>Buku</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status Denda</th><th>Aksi</th></tr></thead>
      <tbody>
      <?php foreach ($antrian_kembali as $p):
        $today = date('Y-m-d');
        $terlambat = $today > $p->tanggal_kembali;
        $hari_lambat = $terlambat ? (int)ceil((strtotime($today)-strtotime($p->tanggal_kembali))/86400) : 0;
        $denda_est = $hari_lambat * 1000;
      ?>
      <tr>
        <td><div style="font-weight:600"><?= htmlspecialchars($p->nama_anggota) ?></div><div style="font-size:.75rem;color:#94a3b8"><?= $p->nim ?></div></td>
        <td><?= htmlspecialchars($p->judul_buku) ?></td>
        <td><?= date('d/m/Y', strtotime($p->tanggal_pinjam)) ?></td>
        <td class="<?= $terlambat ? 'text-danger' : '' ?>" style="<?= $terlambat ? 'color:#dc2626;font-weight:600' : '' ?>">
          <?= date('d/m/Y', strtotime($p->tanggal_kembali)) ?>
          <?php if ($terlambat): ?><div style="font-size:.7rem"><?= $hari_lambat ?> hari terlambat</div><?php endif; ?>
        </td>
        <td>
          <?php if ($terlambat): ?>
          <span class="badge badge-terlambat">~Rp<?= number_format($denda_est,0,',','.') ?></span>
          <?php else: ?>
          <span class="badge badge-dikembalikan">Tepat Waktu</span>
          <?php endif; ?>
        </td>
        <td>
          <a href="<?= base_url('peminjaman/kembalikan/'.$p->id) ?>" class="btn btn-primary btn-sm" onclick="return confirm('Verifikasi pengembalian buku ini?<?= $terlambat ? " Denda Rp".number_format($denda_est,0,",",".") ." akan dicatat." : "" ?>')">
            <span class="material-icons">task_alt</span> Verifikasi
          </a>
        </td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>

<?php if (!empty($pinjam_terlambat_list)): ?>
<div class="card">
  <div class="card-header">
    <span class="material-icons" style="color:#dc2626">alarm</span>
    <h2>Peminjaman Terlambat</h2>
    <span class="badge badge-terlambat"><?= count($pinjam_terlambat_list) ?> buku</span>
  </div>
  <div class="table-wrap">
    <table>
      <thead><tr><th>Anggota</th><th>Buku</th><th>Tgl Kembali</th><th>Keterlambatan</th></tr></thead>
      <tbody>
      <?php foreach ($pinjam_terlambat_list as $p):
        $hari = (int)ceil((time() - strtotime($p->tanggal_kembali))/86400);
      ?>
      <tr>
        <td><?= htmlspecialchars($p->nama_anggota) ?></td>
        <td><?= htmlspecialchars($p->judul_buku) ?></td>
        <td style="color:#dc2626;font-weight:600"><?= date('d/m/Y', strtotime($p->tanggal_kembali)) ?></td>
        <td><span class="badge badge-terlambat"><?= $hari ?> hari &middot; Rp<?= number_format($hari*1000,0,',','.') ?></span></td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>
