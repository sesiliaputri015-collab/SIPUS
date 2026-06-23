<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="page-header">
  <div class="page-icon" style="background:linear-gradient(135deg,#e65100,#ff9800)"><span class="material-icons">swap_horiz</span></div>
  <div><h1><?= $title ?></h1><p><?= count($peminjaman) ?> data peminjaman ditemukan</p></div>
  <?php if (in_array($role,['admin','operator'])): ?>
  <div class="spacer"></div>
  <a href="<?= base_url('peminjaman/tambah') ?>" class="btn btn-primary"><span class="material-icons">add</span> Input Manual</a>
  <?php elseif ($role === 'anggota'): ?>
  <div class="spacer"></div>
  <a href="<?= base_url('peminjaman/ajukan') ?>" class="btn btn-primary"><span class="material-icons">add_circle</span> Pinjam Buku</a>
  <?php endif; ?>
</div>

<!-- Status Tabs -->
<div class="status-tabs">
  <a href="<?= base_url('peminjaman') ?>" class="<?= ($status_aktif==='semua'||!$status_aktif)?'active':'' ?>">Semua</a>
  <a href="<?= base_url('peminjaman?status=menunggu') ?>" class="<?= $status_aktif==='menunggu'?'active':'' ?>">⏳ Menunggu</a>
  <a href="<?= base_url('peminjaman?status=dipinjam') ?>" class="<?= $status_aktif==='dipinjam'?'active':'' ?>">📖 Dipinjam</a>
  <a href="<?= base_url('peminjaman?status=menunggu_kembali') ?>" class="<?= $status_aktif==='menunggu_kembali'?'active':'' ?>">🔄 Menunggu Kembali</a>
  <a href="<?= base_url('peminjaman?status=terlambat') ?>" class="<?= $status_aktif==='terlambat'?'active':'' ?>">⚠️ Terlambat</a>
  <a href="<?= base_url('peminjaman?status=dikembalikan') ?>" class="<?= $status_aktif==='dikembalikan'?'active':'' ?>">✅ Dikembalikan</a>
</div>

<?php if (in_array($role,['admin','operator']) && isset($search)): ?>
<div class="card" style="margin-bottom:1rem">
  <div class="card-body" style="padding:.875rem 1.25rem">
    <form method="GET" action="<?= base_url('peminjaman') ?>"><div class="search-bar">
      <div class="search-input-wrap"><span class="material-icons">search</span>
        <input type="text" name="search" value="<?= htmlspecialchars($search??'') ?>" placeholder="Cari nama anggota, NIM, atau judul buku...">
      </div>
      <input type="hidden" name="status" value="<?= htmlspecialchars($status_aktif) ?>">
      <button type="submit" class="btn btn-primary btn-sm"><span class="material-icons">search</span> Cari</button>
      <a href="<?= base_url('peminjaman') ?>" class="btn btn-secondary btn-sm"><span class="material-icons">refresh</span></a>
    </div></form>
  </div>
</div>
<?php endif; ?>

<div class="card">
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Anggota</th>
          <th>Buku</th>
          <th>Tgl Pinjam</th>
          <th>Tgl Kembali</th>
          <?php if (in_array($role,['admin','operator'])): ?><th>Tgl Aktual</th><?php endif; ?>
          <th>Status</th>
          <th>Denda</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
      <?php if (empty($peminjaman)): ?>
      <tr><td colspan="9">
        <div class="empty-state"><span class="material-icons">swap_horiz</span><p>Tidak ada data peminjaman</p></div>
      </td></tr>
      <?php else: $no=1; foreach ($peminjaman as $p):
        $today     = date('Y-m-d');
        $terlambat = in_array($p->status,['Terlambat']) || ($p->status==='Dipinjam' && $today > $p->tanggal_kembali);
        $badge_map = [
          'Menunggu'         => 'badge-menunggu',
          'Dipinjam'         => 'badge-dipinjam',
          'Menunggu Kembali' => 'badge-menunggu-kembali',
          'Terlambat'        => 'badge-terlambat',
          'Dikembalikan'     => 'badge-dikembalikan',
        ];
        $badge_icon = [
          'Menunggu'         => 'schedule',
          'Dipinjam'         => 'book',
          'Menunggu Kembali' => 'assignment_return',
          'Terlambat'        => 'alarm',
          'Dikembalikan'     => 'check_circle',
        ];
        $bc = $badge_map[$p->status] ?? 'badge-menunggu';
        $bi = $badge_icon[$p->status] ?? 'schedule';
      ?>
      <tr>
        <td style="color:#94a3b8"><?= $no++ ?></td>
        <td>
          <div style="font-weight:600"><?= htmlspecialchars($p->nama_anggota) ?></div>
          <div style="font-size:.75rem;color:#94a3b8"><?= $p->nim ?></div>
        </td>
        <td style="max-width:180px">
          <div style="font-weight:500;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= htmlspecialchars($p->judul_buku) ?></div>
          <div style="font-size:.75rem;color:#94a3b8"><?= htmlspecialchars($p->penulis) ?></div>
        </td>
        <td style="font-size:.8375rem"><?= date('d/m/Y', strtotime($p->tanggal_pinjam)) ?></td>
        <td style="font-size:.8375rem;<?= $terlambat?'color:#dc2626;font-weight:600':'' ?>">
          <?= date('d/m/Y', strtotime($p->tanggal_kembali)) ?>
          <?php if ($terlambat && $p->tanggal_aktual === null): ?>
          <?php $hari = (int)ceil((strtotime($today)-strtotime($p->tanggal_kembali))/86400); ?>
          <div style="font-size:.7rem"><?= $hari ?>h terlambat</div>
          <?php endif; ?>
        </td>
        <?php if (in_array($role,['admin','operator'])): ?>
        <td style="font-size:.8375rem"><?= $p->tanggal_aktual ? date('d/m/Y', strtotime($p->tanggal_aktual)) : '—' ?></td>
        <?php endif; ?>
        <td><span class="badge <?= $bc ?>"><span class="material-icons" style="font-size:11px"><?= $bi ?></span><?= $p->status ?></span></td>
        <td><?php if ($p->denda > 0): ?><span style="font-weight:700;color:#dc2626">Rp<?= number_format($p->denda,0,',','.') ?></span><?php else: ?><span style="color:#94a3b8">—</span><?php endif; ?></td>
        <td>
          <?php if (in_array($role,['admin','operator'])): ?>
            <?php if ($p->status === 'Menunggu'): ?>
            <a href="<?= base_url('peminjaman/setujui/'.$p->id) ?>" class="btn btn-success btn-sm" onclick="return confirm('Setujui?')"><span class="material-icons">check</span></a>
            <a href="<?= base_url('peminjaman/tolak/'.$p->id) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tolak?')"><span class="material-icons">close</span></a>
            <?php elseif (in_array($p->status,['Menunggu Kembali','Dipinjam','Terlambat'])): ?>
            <a href="<?= base_url('peminjaman/kembalikan/'.$p->id) ?>" class="btn btn-primary btn-sm" onclick="return confirm('Verifikasi pengembalian?')"><span class="material-icons">task_alt</span></a>
            <?php endif; ?>
            <?php if ($role==='admin'): ?>
            <a href="<?= base_url('peminjaman/hapus/'.$p->id) ?>" class="btn btn-danger btn-sm btn-icon" onclick="return confirm('Hapus data ini?')"><span class="material-icons">delete</span></a>
            <?php endif; ?>
          <?php elseif ($role === 'anggota'): ?>
            <?php if (in_array($p->status,['Dipinjam','Terlambat'])): ?>
            <a href="<?= base_url('peminjaman/ajukan_kembali/'.$p->id) ?>" class="btn btn-warning btn-sm" onclick="return confirm('Ajukan pengembalian?')"><span class="material-icons">assignment_return</span> Kembalikan</a>
            <?php elseif ($p->status === 'Menunggu'): ?>
            <span style="font-size:.75rem;color:#94a3b8">Menunggu operator</span>
            <?php elseif ($p->status === 'Menunggu Kembali'): ?>
            <span style="font-size:.75rem;color:#64748b">Menunggu verifikasi</span>
            <?php endif; ?>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
