<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="page-header">
  <div class="page-icon" style="background:linear-gradient(135deg,#0f4c81,#1976d2)"><span class="material-icons">dashboard</span></div>
  <div><h1>Dashboard Admin</h1><p>Selamat datang, <?= htmlspecialchars($user->nama) ?>. Berikut ringkasan sistem perpustakaan.</p></div>
</div>

<div class="stats-grid cols-4">
  <div class="stat-card blue">
    <div class="stat-icon blue"><span class="material-icons">menu_book</span></div>
    <div class="stat-info"><div class="value"><?= $total_buku ?></div><div class="label">Total Buku</div></div>
  </div>
  <div class="stat-card green">
    <div class="stat-icon green"><span class="material-icons">check_circle</span></div>
    <div class="stat-info"><div class="value"><?= $buku_tersedia ?></div><div class="label">Buku Tersedia</div></div>
  </div>
  <div class="stat-card teal">
    <div class="stat-icon teal"><span class="material-icons">groups</span></div>
    <div class="stat-info"><div class="value"><?= $anggota_aktif ?></div><div class="label">Anggota Aktif</div></div>
  </div>
  <div class="stat-card purple">
    <div class="stat-icon purple"><span class="material-icons">people</span></div>
    <div class="stat-info"><div class="value"><?= $total_admin + $total_operator + $total_user_anggota ?></div><div class="label">Total User</div></div>
  </div>
</div>

<div class="stats-grid cols-4">
  <div class="stat-card orange">
    <div class="stat-icon orange"><span class="material-icons">pending_actions</span></div>
    <div class="stat-info"><div class="value"><?= $pinjam_menunggu ?></div><div class="label">Menunggu Persetujuan</div></div>
  </div>
  <div class="stat-card blue">
    <div class="stat-icon indigo"><span class="material-icons">swap_horiz</span></div>
    <div class="stat-info"><div class="value"><?= $pinjam_aktif ?></div><div class="label">Sedang Dipinjam</div></div>
  </div>
  <div class="stat-card red">
    <div class="stat-icon red"><span class="material-icons">alarm</span></div>
    <div class="stat-info"><div class="value"><?= $pinjam_terlambat ?></div><div class="label">Terlambat</div></div>
  </div>
  <div class="stat-card amber">
    <div class="stat-icon amber"><span class="material-icons">receipt_long</span></div>
    <div class="stat-info"><div class="value" style="font-size:1.2rem">Rp<?= number_format($total_denda,0,',','.') ?></div><div class="label">Denda Belum Bayar</div></div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1.6fr;gap:1rem;margin-bottom:1rem" class="dash-grid-2">

  <div class="card">
    <div class="card-header">
      <span class="material-icons" style="color:#6d28d9">people</span>
      <h2>Distribusi User</h2>
    </div>
    <div class="card-body" style="padding:.75rem">
      <?php
      $roles_stat = [
        ['label'=>'Admin',    'count'=>$total_admin,        'cls'=>'badge-admin',    'icon'=>'shield'],
        ['label'=>'Operator', 'count'=>$total_operator,     'cls'=>'badge-operator', 'icon'=>'manage_accounts'],
        ['label'=>'Anggota',  'count'=>$total_user_anggota, 'cls'=>'badge-anggota',  'icon'=>'person'],
      ];
      foreach ($roles_stat as $rs): ?>
      <div style="display:flex;align-items:center;gap:.75rem;padding:.625rem .5rem;border-radius:8px" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
        <span class="material-icons" style="color:#94a3b8"><?= $rs['icon'] ?></span>
        <span style="flex:1;font-size:.875rem;font-weight:500"><?= $rs['label'] ?></span>
        <span class="badge <?= $rs['cls'] ?>"><?= $rs['count'] ?> user</span>
        <a href="<?= base_url('user?role='.$rs['label']) ?>" class="btn btn-secondary btn-sm btn-icon"><span class="material-icons">arrow_forward</span></a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <span class="material-icons" style="color:#d97706">pending_actions</span>
      <h2>Antrian Persetujuan Peminjaman</h2>
      <a href="<?= base_url('peminjaman?status=menunggu') ?>" class="btn btn-secondary btn-sm">Lihat Semua</a>
    </div>
    <?php if (empty($pinjam_menunggu_list)): ?>
    <div class="empty-state"><span class="material-icons">check_circle</span><p>Tidak ada antrian peminjaman</p></div>
    <?php else: ?>
    <div class="table-wrap">
      <table>
        <thead><tr><th>Anggota</th><th>Buku</th><th>Tgl Pinjam</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php foreach (array_slice($pinjam_menunggu_list, 0, 5) as $p): ?>
        <tr>
          <td><div style="font-weight:600"><?= htmlspecialchars($p->nama_anggota) ?></div><div style="font-size:.75rem;color:#94a3b8"><?= $p->nim ?></div></td>
          <td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= htmlspecialchars($p->judul_buku) ?></td>
          <td><?= date('d/m/Y', strtotime($p->tanggal_pinjam)) ?></td>
          <td style="white-space:nowrap">
            <a href="<?= base_url('peminjaman/setujui/'.$p->id) ?>" class="btn btn-success btn-sm" onclick="return confirm('Setujui peminjaman ini?')">
              <span class="material-icons">check</span> <span class="hide-xs">Setujui</span>
            </a>
            <a href="<?= base_url('peminjaman/tolak/'.$p->id) ?>" class="btn btn-danger btn-sm btn-icon" onclick="return confirm('Tolak peminjaman ini?')">
              <span class="material-icons">close</span>
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
</div>

<div class="card" style="margin-bottom:1rem">
  <div class="card-header">
    <span class="material-icons" style="color:#0d6efd">pie_chart</span>
    <h2>Distribusi Buku per Kategori</h2>
    <a href="<?= base_url('kategori') ?>" class="btn btn-secondary btn-sm">Kelola Kategori</a>
  </div>
  <div class="card-body">
    <?php
    $colors = ['#1976d2','#2e7d32','#e65100','#6a1b9a','#c62828','#00695c'];
    $max    = max(array_values($kategori_distrib) + [1]);
    $i = 0;
    foreach ($kategori_distrib as $nama => $jml): $c = $colors[$i % count($colors)]; $i++; ?>
    <div style="margin-bottom:.75rem">
      <div style="display:flex;justify-content:space-between;font-size:.8125rem;margin-bottom:.3rem">
        <span style="font-weight:500"><?= htmlspecialchars($nama) ?></span>
        <span style="color:#64748b"><?= $jml ?> buku</span>
      </div>
      <div style="background:#f1f5f9;border-radius:20px;height:8px;overflow:hidden">
        <div style="background:<?= $c ?>;height:100%;width:<?= ($max > 0 ? round($jml/$max*100) : 0) ?>%;border-radius:20px;transition:width .4s"></div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-top:1rem" class="dash-grid-4">
  <?php $links = [
    ['url'=>'user/tambah','icon'=>'person_add','label'=>'Tambah User','color'=>'#4527a0'],
    ['url'=>'buku/tambah','icon'=>'add_box','label'=>'Tambah Buku','color'=>'#1565c0'],
    ['url'=>'anggota/tambah','icon'=>'group_add','label'=>'Tambah Anggota','color'=>'#2e7d32'],
    ['url'=>'peminjaman/tambah','icon'=>'post_add','label'=>'Input Peminjaman','color'=>'#e65100'],
  ];
  foreach ($links as $l): ?>
  <a href="<?= base_url($l['url']) ?>" class="card" style="display:flex;align-items:center;gap:.875rem;padding:1rem;text-decoration:none;transition:transform .15s" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">
    <div style="width:40px;height:40px;border-radius:10px;background:<?= $l['color'] ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0">
      <span class="material-icons" style="color:#fff;font-size:20px"><?= $l['icon'] ?></span>
    </div>
    <span style="font-size:.875rem;font-weight:600;color:#1e293b"><?= $l['label'] ?></span>
  </a>
  <?php endforeach; ?>
</div>
