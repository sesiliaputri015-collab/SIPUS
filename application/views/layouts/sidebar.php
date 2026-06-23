<?php
/*
 * sidebar.php — di-load SETELAH header.php, SEBELUM content view
 * Posisi: masuk ke dalam .app-wrapper, sebelum .main-wrapper
 *
 * Tapi karena header.php sudah membuka .app-wrapper dan .main-wrapper,
 * sidebar ini perlu diposisikan dengan CSS fixed/absolute.
 *
 * PENTING: Lihat catatan di footer.php tentang urutan HTML.
 * Sidebar di-render sebagai fixed drawer — tidak mempengaruhi flow dokumen.
 */

$seg1 = $this->uri->segment(1);
$pinjam_menunggu = 0;
$pinjam_kembali  = 0;
if (isset($this->Peminjaman_model)) {
  $pinjam_menunggu = $this->Peminjaman_model->count_menunggu();
  $pinjam_kembali  = $this->Peminjaman_model->count_menunggu_kembali();
}
$role_label = ['admin'=>'Administrator','operator'=>'Operator','anggota'=>'Anggota'][$role] ?? ucfirst($role ?? '');
?>
<style>
/* ── SIDEBAR (diletakkan via fixed, tidak ganggu flex layout) ── */
.sidebar {
  position:fixed; top:0; left:0; height:100vh; width:260px;
  background:#0f172a; color:#fff; display:flex; flex-direction:column;
  overflow-y:auto; overflow-x:hidden; z-index:200;
  transition:transform .25s ease;
  scrollbar-width:thin; scrollbar-color:rgba(255,255,255,.1) transparent;
}
.sidebar::-webkit-scrollbar { width:4px; }
.sidebar::-webkit-scrollbar-thumb { background:rgba(255,255,255,.1); border-radius:4px; }

/* Desktop: sidebar mendorong main-wrapper ke kanan via margin */
@media (min-width:768px) {
  .sidebar { transform:translateX(0) !important; }
  .main-wrapper { margin-left:260px; }
}
@media (min-width:768px) and (max-width:1199px) {
  .sidebar { width:220px; }
  .main-wrapper { margin-left:220px; }
}

/* Mobile: drawer */
@media (max-width:767px) {
  .sidebar { width:280px; transform:translateX(-100%); box-shadow:none; }
  .sidebar.open { transform:translateX(0); box-shadow:4px 0 30px rgba(0,0,0,.3); }
  .main-wrapper { margin-left:0 !important; }
  .btn-close-sidebar { display:flex !important; }
}

.sidebar-brand {
  display:flex; align-items:center; gap:.75rem;
  padding:1.25rem; border-bottom:1px solid rgba(255,255,255,.08); flex-shrink:0;
}
.brand-icon {
  width:38px; height:38px; border-radius:10px; flex-shrink:0;
  background:linear-gradient(135deg,#2563eb,#1d4ed8);
  display:flex; align-items:center; justify-content:center;
}
.brand-icon .material-icons { color:#fff; font-size:20px; }
.brand-text h2  { font-size:1.05rem; font-weight:700; color:#fff; }
.brand-text span{ font-size:.7rem; color:#94a3b8; }
.btn-close-sidebar {
  display:none; margin-left:auto; color:#94a3b8;
  width:32px; height:32px; border-radius:8px;
  align-items:center; justify-content:center; transition:background .15s;
}
.btn-close-sidebar:hover { background:rgba(255,255,255,.1); }

.sidebar-user {
  display:flex; align-items:center; gap:.75rem;
  padding:1rem 1.25rem; border-bottom:1px solid rgba(255,255,255,.08); flex-shrink:0;
}
.sidebar-user .avatar .material-icons { font-size:36px; color:#64748b; }
.u-name { font-size:.85rem; font-weight:600; color:#fff; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.u-role { font-size:.7rem; color:#94a3b8; text-transform:uppercase; letter-spacing:.04em; }

.nav-section { padding:.75rem 0; }
.nav-label {
  padding:.4rem 1.25rem; font-size:.68rem; font-weight:700;
  text-transform:uppercase; letter-spacing:.05em; color:#64748b;
}
.nav-item a {
  display:flex; align-items:center; gap:.85rem; padding:.65rem 1.25rem;
  color:#cbd5e1; font-size:.875rem; font-weight:500;
  position:relative; transition:background .15s, color .15s;
}
.nav-item a:hover  { background:rgba(255,255,255,.05); color:#fff; }
.nav-item a.active { background:rgba(37,99,235,.18); color:#60a5fa; border-right:3px solid #2563eb; }
.nav-item a .material-icons { font-size:20px; flex-shrink:0; }
.nav-item a span:nth-child(2) { flex:1; }
.nav-badge {
  background:#dc2626; color:#fff; font-size:.65rem; font-weight:700;
  padding:.15rem .45rem; border-radius:10px; min-width:18px; text-align:center;
}

.sidebar-footer { margin-top:auto; border-top:1px solid rgba(255,255,255,.08); padding:.5rem 0; flex-shrink:0; }
.sidebar-footer a {
  display:flex; align-items:center; gap:.85rem; padding:.65rem 1.25rem;
  color:#cbd5e1; font-size:.875rem; font-weight:500;
  transition:background .15s, color .15s;
}
.sidebar-footer a:hover { background:rgba(255,255,255,.05); color:#fff; }
.sidebar-footer a .material-icons { font-size:20px; flex-shrink:0; }
</style>

<nav class="sidebar" id="sidebar">

  <div class="sidebar-brand">
    <div class="brand-icon"><span class="material-icons">local_library</span></div>
    <div class="brand-text"><h2>SIPUS</h2><span>Perpustakaan</span></div>
    <button class="btn-close-sidebar" onclick="closeSidebar()" aria-label="Tutup">
      <span class="material-icons">close</span>
    </button>
  </div>

  <div class="sidebar-user">
    <div class="avatar"><span class="material-icons">account_circle</span></div>
    <div class="u-info">
      <div class="u-name"><?= htmlspecialchars($user->nama ?? '') ?></div>
      <div class="u-role"><?= $role_label ?></div>
    </div>
  </div>

  <?php if ($role === 'admin'): ?>

  <div class="nav-section">
    <div class="nav-label">Utama</div>
    <ul>
      <li class="nav-item">
        <a href="<?= base_url('dashboard') ?>" class="<?= $seg1==='dashboard'?'active':'' ?>">
          <span class="material-icons">dashboard</span><span>Dashboard</span>
        </a>
      </li>
    </ul>
  </div>
  <div class="nav-section">
    <div class="nav-label">Kelola Sistem</div>
    <ul>
      <li class="nav-item">
        <a href="<?= base_url('user') ?>" class="<?= $seg1==='user'?'active':'' ?>">
          <span class="material-icons">manage_accounts</span><span>Manajemen User</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="<?= base_url('kategori') ?>" class="<?= $seg1==='kategori'?'active':'' ?>">
          <span class="material-icons">category</span><span>Kategori Buku</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="<?= base_url('buku') ?>" class="<?= $seg1==='buku'?'active':'' ?>">
          <span class="material-icons">menu_book</span><span>Data Buku</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="<?= base_url('anggota') ?>" class="<?= $seg1==='anggota'?'active':'' ?>">
          <span class="material-icons">groups</span><span>Data Anggota</span>
        </a>
      </li>
    </ul>
  </div>
  <div class="nav-section">
    <div class="nav-label">Transaksi</div>
    <ul>
      <li class="nav-item">
        <a href="<?= base_url('peminjaman') ?>" class="<?= $seg1==='peminjaman'?'active':'' ?>">
          <span class="material-icons">swap_horiz</span><span>Peminjaman</span>
          <?php if ($pinjam_menunggu > 0): ?><span class="nav-badge"><?= $pinjam_menunggu ?></span><?php endif; ?>
        </a>
      </li>
      <li class="nav-item">
        <a href="<?= base_url('denda') ?>" class="<?= $seg1==='denda'?'active':'' ?>">
          <span class="material-icons">receipt_long</span><span>Denda</span>
        </a>
      </li>
    </ul>
  </div>

  <?php elseif ($role === 'operator'): ?>

  <div class="nav-section">
    <div class="nav-label">Utama</div>
    <ul>
      <li class="nav-item">
        <a href="<?= base_url('dashboard') ?>" class="<?= $seg1==='dashboard'?'active':'' ?>">
          <span class="material-icons">dashboard</span><span>Dashboard</span>
        </a>
      </li>
    </ul>
  </div>
  <div class="nav-section">
    <div class="nav-label">Antrian Proses</div>
    <ul>
      <li class="nav-item">
        <a href="<?= base_url('peminjaman?status=menunggu') ?>">
          <span class="material-icons">pending_actions</span><span>Setujui Pinjam</span>
          <?php if ($pinjam_menunggu > 0): ?><span class="nav-badge"><?= $pinjam_menunggu ?></span><?php endif; ?>
        </a>
      </li>
      <li class="nav-item">
        <a href="<?= base_url('peminjaman?status=menunggu_kembali') ?>">
          <span class="material-icons">assignment_return</span><span>Verif. Kembali</span>
          <?php if ($pinjam_kembali > 0): ?><span class="nav-badge"><?= $pinjam_kembali ?></span><?php endif; ?>
        </a>
      </li>
    </ul>
  </div>
  <div class="nav-section">
    <div class="nav-label">Data</div>
    <ul>
      <li class="nav-item">
        <a href="<?= base_url('buku') ?>" class="<?= $seg1==='buku'?'active':'' ?>">
          <span class="material-icons">menu_book</span><span>Data Buku</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="<?= base_url('anggota') ?>" class="<?= $seg1==='anggota'?'active':'' ?>">
          <span class="material-icons">groups</span><span>Data Anggota</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="<?= base_url('peminjaman') ?>" class="<?= $seg1==='peminjaman'?'active':'' ?>">
          <span class="material-icons">swap_horiz</span><span>Semua Peminjaman</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="<?= base_url('denda') ?>" class="<?= $seg1==='denda'?'active':'' ?>">
          <span class="material-icons">receipt_long</span><span>Denda</span>
        </a>
      </li>
    </ul>
  </div>

  <?php elseif ($role === 'anggota'): ?>

  <div class="nav-section">
    <div class="nav-label">Menu Saya</div>
    <ul>
      <li class="nav-item">
        <a href="<?= base_url('dashboard') ?>" class="<?= $seg1==='dashboard'?'active':'' ?>">
          <span class="material-icons">home</span><span>Beranda</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="<?= base_url('peminjaman/ajukan') ?>"
           class="<?= ($seg1==='peminjaman' && $this->uri->segment(2)==='ajukan')?'active':'' ?>">
          <span class="material-icons">add_circle</span><span>Pinjam Buku</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="<?= base_url('peminjaman') ?>"
           class="<?= ($seg1==='peminjaman' && $this->uri->segment(2)!=='ajukan')?'active':'' ?>">
          <span class="material-icons">history</span><span>Riwayat Pinjam</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="<?= base_url('denda') ?>" class="<?= $seg1==='denda'?'active':'' ?>">
          <span class="material-icons">receipt_long</span><span>Denda Saya</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="<?= base_url('buku') ?>" class="<?= $seg1==='buku'?'active':'' ?>">
          <span class="material-icons">import_contacts</span><span>Katalog Buku</span>
        </a>
      </li>
    </ul>
  </div>

  <?php endif; ?>

  <div class="sidebar-footer">
    <a href="<?= base_url('profil') ?>">
      <span class="material-icons">settings</span><span>Profil</span>
    </a>
    <a href="<?= base_url('auth/logout') ?>">
      <span class="material-icons">logout</span><span>Logout</span>
    </a>
  </div>

</nav>
