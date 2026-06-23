<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <title><?= isset($title) ? $title.' - SIPUS' : 'SIPUS Perpustakaan' ?></title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <style>
  *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
  html { height:100%; -webkit-text-size-adjust:100%; }
  body {
    font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;
    background:#f1f5f9; color:#1e293b; font-size:14px; line-height:1.5;
    height:100%; overflow:hidden;
  }
  a { text-decoration:none; color:inherit; }
  ul { list-style:none; }
  table { width:100%; border-collapse:collapse; }
  button { font-family:inherit; cursor:pointer; border:none; background:none; }
  input, select, textarea { font-family:inherit; }

  /* ── APP SHELL ── */
  .app-wrapper { display:flex; height:100vh; width:100vw; overflow:hidden; }

  /* ── MAIN WRAPPER (kanan) ── */
  .main-wrapper { flex:1; min-width:0; display:flex; flex-direction:column; height:100vh; overflow:hidden; }

  /* ── TOPBAR ── */
  .topbar {
    height:64px; background:#fff; border-bottom:1px solid #e2e8f0;
    display:flex; align-items:center; gap:1rem; padding:0 1.5rem;
    z-index:100; flex-shrink:0;
  }
  .btn-hamburger {
    display:none; width:36px; height:36px; border-radius:8px;
    align-items:center; justify-content:center; color:#475569; flex-shrink:0;
    transition:background .15s;
  }
  .btn-hamburger:hover { background:#f1f5f9; }
  .topbar-title {
    font-size:1.05rem; font-weight:700; color:#1e293b; flex:1;
    min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;
  }
  .role-chip {
    display:flex; align-items:center; gap:.3rem;
    padding:.3rem .75rem; border-radius:20px; font-size:.75rem; font-weight:600; flex-shrink:0;
  }
  .chip-admin    { background:#dbeafe; color:#1d4ed8; }
  .chip-operator { background:#dcfce7; color:#15803d; }
  .chip-anggota  { background:#f3e8ff; color:#7e22ce; }
  .topbar-user {
    display:flex; align-items:center; gap:.5rem; padding:.4rem .6rem;
    border-radius:10px; color:#1e293b; flex-shrink:0; transition:background .15s;
  }
  .topbar-user:hover { background:#f1f5f9; }
  .topbar-user .material-icons { font-size:28px; color:#64748b; }
  .topbar-user .uname { font-size:.85rem; font-weight:600; white-space:nowrap; }
  .topbar-logout {
    display:flex; align-items:center; justify-content:center;
    width:36px; height:36px; border-radius:8px; color:#64748b; flex-shrink:0;
    transition:background .15s, color .15s;
  }
  .topbar-logout:hover { background:#fee2e2; color:#dc2626; }
  .topbar-logout .material-icons { font-size:20px; }

  /* ── CONTENT ── */
  .content {
    flex:1; overflow-y:auto; overflow-x:hidden; padding:1.5rem;
    scrollbar-width:thin; scrollbar-color:#cbd5e1 transparent;
  }
  .content::-webkit-scrollbar { width:6px; }
  .content::-webkit-scrollbar-thumb { background:#cbd5e1; border-radius:4px; }

  /* ── FOOTER ── */
  .footer {
    flex-shrink:0; background:#fff; border-top:1px solid #e2e8f0;
    text-align:center; padding:.75rem 1.5rem; font-size:.75rem; color:#94a3b8;
  }

  /* ── OVERLAY (mobile) ── */
  .sidebar-overlay {
    display:none; position:fixed; inset:0; background:rgba(0,0,0,.5);
    z-index:190; opacity:0; transition:opacity .25s;
  }
  .sidebar-overlay.active { display:block; opacity:1; }

  /* ── PAGE HEADER ── */
  .page-header {
    display:flex; align-items:center; gap:1rem; margin-bottom:1.25rem; flex-wrap:wrap;
  }
  .page-icon {
    width:48px; height:48px; border-radius:12px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center;
  }
  .page-icon .material-icons { color:#fff; font-size:24px; }
  .page-header h1 { font-size:1.25rem; font-weight:700; color:#1e293b; }
  .page-header p  { font-size:.85rem; color:#64748b; margin-top:.15rem; }
  .page-header .spacer { flex:1; }
  .page-header-actions { display:flex; gap:.5rem; flex-wrap:wrap; }

  /* ── STATS GRID ── */
  .stats-grid { display:grid; gap:1rem; margin-bottom:1.5rem; }
  .stats-grid.cols-4 { grid-template-columns:repeat(4,1fr); }
  .stats-grid.cols-3 { grid-template-columns:repeat(3,1fr); }
  .stats-grid.cols-2 { grid-template-columns:repeat(2,1fr); }
  .stat-card {
    background:#fff; border-radius:16px; padding:1.25rem;
    display:flex; align-items:center; gap:1rem;
    box-shadow:0 1px 3px rgba(0,0,0,.06); position:relative; overflow:hidden; min-width:0;
  }
  .stat-icon {
    width:48px; height:48px; border-radius:12px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center;
  }
  .stat-icon .material-icons { font-size:24px; color:#fff; }
  .stat-icon.blue   { background:#2563eb; } .stat-icon.green  { background:#16a34a; }
  .stat-icon.teal   { background:#0d9488; } .stat-icon.purple { background:#7c3aed; }
  .stat-icon.orange { background:#ea580c; } .stat-icon.red    { background:#dc2626; }
  .stat-icon.amber  { background:#d97706; } .stat-icon.indigo { background:#4f46e5; }
  .stat-info .value { font-size:1.75rem; font-weight:700; color:#1e293b; }
  .stat-info .label { font-size:.8rem; color:#64748b; margin-top:.1rem; }
  .stat-card::before { content:''; position:absolute; top:0; left:0; width:4px; height:100%; }
  .stat-card.blue::before   { background:#2563eb; } .stat-card.green::before  { background:#16a34a; }
  .stat-card.teal::before   { background:#0d9488; } .stat-card.purple::before { background:#7c3aed; }
  .stat-card.orange::before { background:#ea580c; } .stat-card.red::before    { background:#dc2626; }
  .stat-card.amber::before  { background:#d97706; } .stat-card.indigo::before { background:#4f46e5; }

  /* ── CARD ── */
  .card { background:#fff; border-radius:16px; box-shadow:0 1px 3px rgba(0,0,0,.06); overflow:hidden; margin-bottom:1rem; }
  .card-header { display:flex; align-items:center; gap:.6rem; padding:1rem 1.25rem; border-bottom:1px solid #f1f5f9; flex-wrap:wrap; }
  .card-header h2 { font-size:.95rem; font-weight:700; color:#1e293b; flex:1; }
  .card-body { padding:1rem 1.25rem; }

  /* ── TABLE ── */
  .table-wrap { width:100%; overflow-x:auto; -webkit-overflow-scrolling:touch; }
  .table-wrap table { min-width:600px; }
  thead th {
    text-align:left; padding:.75rem 1rem; font-size:.75rem; font-weight:700;
    text-transform:uppercase; letter-spacing:.03em; color:#64748b;
    background:#f8fafc; border-bottom:1px solid #e2e8f0; white-space:nowrap;
  }
  tbody td { padding:.75rem 1rem; font-size:.85rem; border-bottom:1px solid #f1f5f9; vertical-align:middle; }
  tbody tr:last-child td { border-bottom:none; }
  tbody tr:hover { background:#f8fafc; }

  /* ── BADGE ── */
  .badge {
    display:inline-flex; align-items:center; gap:.25rem;
    padding:.25rem .65rem; border-radius:20px; font-size:.72rem; font-weight:600; white-space:nowrap;
  }
  .badge-admin        { background:#dbeafe; color:#1d4ed8; }
  .badge-operator     { background:#dcfce7; color:#15803d; }
  .badge-anggota      { background:#f3e8ff; color:#7e22ce; }
  .badge-menunggu     { background:#fef3c7; color:#b45309; }
  .badge-dipinjam     { background:#dbeafe; color:#1d4ed8; }
  .badge-terlambat    { background:#fee2e2; color:#dc2626; }
  .badge-dikembalikan { background:#dcfce7; color:#15803d; }
  .badge-lunas        { background:#dcfce7; color:#15803d; }
  .badge-belum        { background:#fee2e2; color:#dc2626; }
  .badge-aktif        { background:#dcfce7; color:#15803d; }
  .badge-nonaktif     { background:#f1f5f9; color:#64748b; }

  /* ── BUTTON ── */
  .btn {
    display:inline-flex; align-items:center; gap:.4rem;
    padding:.55rem 1rem; border-radius:10px; font-size:.85rem; font-weight:600;
    white-space:nowrap; transition:opacity .15s, transform .1s; cursor:pointer;
  }
  .btn:active  { transform:scale(.97); }
  .btn:hover   { opacity:.88; }
  .btn .material-icons { font-size:18px; }
  .btn-primary   { background:#2563eb; color:#fff; }
  .btn-secondary { background:#f1f5f9; color:#475569; }
  .btn-success   { background:#16a34a; color:#fff; }
  .btn-danger    { background:#dc2626; color:#fff; }
  .btn-warning   { background:#d97706; color:#fff; }
  .btn-info      { background:#0891b2; color:#fff; }
  .btn-sm   { padding:.4rem .75rem; font-size:.78rem; }
  .btn-icon { padding:.5rem; width:38px; height:38px; justify-content:center; }

  /* ── FORM ── */
  .form-row { display:grid; gap:1rem; margin-bottom:1rem; }
  .form-row.cols-2 { grid-template-columns:repeat(2,1fr); }
  .form-row.cols-3 { grid-template-columns:repeat(3,1fr); }
  .form-group { display:flex; flex-direction:column; gap:.35rem; }
  .form-label { font-size:.82rem; font-weight:600; color:#374151; }
  .form-control {
    width:100%; padding:.6rem .85rem; border:1px solid #e2e8f0; border-radius:10px;
    font-size:.875rem; color:#1e293b; background:#fff; transition:border-color .15s;
  }
  .form-control:focus { outline:none; border-color:#2563eb; box-shadow:0 0 0 3px rgba(37,99,235,.1); }
  .form-footer { display:flex; justify-content:flex-end; gap:.6rem; margin-top:1.5rem; }

  /* ── SEARCH & TABS ── */
  .search-bar { display:flex; gap:.6rem; margin-bottom:1rem; flex-wrap:wrap; }
  .search-input-wrap { position:relative; flex:1; min-width:200px; }
  .search-input-wrap input { padding-left:2.25rem; }
  .search-input-wrap .material-icons { position:absolute; left:.65rem; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:20px; }
  .status-tabs {
    display:flex; flex-wrap:nowrap; overflow-x:auto; gap:.4rem;
    margin-bottom:1.25rem; padding-bottom:.5rem; border-bottom:1px solid #e2e8f0;
  }
  .status-tabs a {
    flex:0 0 auto; white-space:nowrap; padding:.5rem 1rem; font-size:.85rem;
    font-weight:600; color:#64748b; border-bottom:2px solid transparent; border-radius:8px 8px 0 0;
  }
  .status-tabs a.active { color:#2563eb; border-color:#2563eb; background:#eff6ff; }

  /* ── FLASH ── */
  .flash {
    display:flex; align-items:center; gap:.6rem; padding:.85rem 1.25rem;
    border-radius:12px; font-size:.85rem; font-weight:500; margin-bottom:1rem;
  }
  .flash-sukses { background:#dcfce7; color:#15803d; }
  .flash-error  { background:#fee2e2; color:#dc2626; }

  /* ── EMPTY STATE ── */
  .empty-state { text-align:center; padding:3rem 1.5rem; color:#94a3b8; }
  .empty-state .material-icons { font-size:48px; margin-bottom:.75rem; opacity:.5; }

  /* ── MISC GRIDS ── */
  .dash-grid-2 { display:grid; gap:1rem; grid-template-columns:1fr 1fr; }
  .profil-grid { display:grid; gap:1rem; grid-template-columns:1fr 1fr; }

  /* ════════════════════════════════════
     RESPONSIVE
  ════════════════════════════════════ */
  @media (max-width:1199px) {
    .stats-grid.cols-4 { grid-template-columns:repeat(2,1fr); }
    .dash-grid-2 { grid-template-columns:1fr; }
  }
  @media (max-width:767px) {
    .app-wrapper { overflow:hidden; }
    .btn-hamburger { display:flex !important; }
    .topbar { height:54px; padding:0 .75rem; gap:.5rem; }
    .topbar-title { font-size:.875rem; }
    .role-chip { display:none; }
    .topbar-user .uname { display:none; }
    .topbar-user { padding:.3rem .35rem; }
    .content { padding:.75rem; }
    .stats-grid.cols-4,
    .stats-grid.cols-3 { grid-template-columns:repeat(2,1fr) !important; }
    .stat-card { padding:1rem !important; }
    .stat-icon { width:40px !important; height:40px !important; }
    .stat-info .value { font-size:1.4rem !important; }
    .stat-card::before { display:none !important; }
    .dash-grid-2,
    .profil-grid { grid-template-columns:1fr !important; }
    .card-header { padding:.75rem .875rem !important; }
    .card-body   { padding:.875rem !important; }
    .form-row.cols-2,
    .form-row.cols-3 { grid-template-columns:1fr; }
    .form-control    { font-size:16px !important; min-height:44px !important; }
    .form-footer     { flex-direction:column; }
    .form-footer .btn { width:100%; justify-content:center; }
    .page-header  { flex-direction:column; align-items:flex-start; }
    .page-header .spacer { display:none; }
    .page-header-actions { width:100%; }
    .page-header-actions .btn { flex:1; justify-content:center; }
    .footer { padding:.5rem .75rem; font-size:.7rem; }
  }
  @media (max-width:399px) {
    .content { padding:.5rem; }
    .stats-grid { gap:.375rem !important; }
    .stat-card  { padding:.75rem !important; }
  }
  @media (max-width:320px) {
    .stats-grid.cols-4,
    .stats-grid.cols-3,
    .stats-grid.cols-2 { grid-template-columns:1fr !important; }
  }
  </style>
</head>
<body>

<?php
$chip_map   = ['admin'=>'chip-admin','operator'=>'chip-operator','anggota'=>'chip-anggota'];
$chip_cls   = $chip_map[$role] ?? 'chip-admin';
$role_label = ['admin'=>'Administrator','operator'=>'Operator','anggota'=>'Anggota'][$role] ?? ucfirst($role ?? '');
$role_icon  = $role === 'admin' ? 'shield' : ($role === 'operator' ? 'manage_accounts' : 'person');
?>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<div class="app-wrapper">

  <!-- SIDEBAR di-load terpisah via $this->load->view('sidebar', $data) -->

  <div class="main-wrapper">
    <header class="topbar">
      <button class="btn-hamburger" onclick="openSidebar()" aria-label="Buka menu">
        <span class="material-icons">menu</span>
      </button>
      <span class="topbar-title"><?= htmlspecialchars($title ?? 'SIPUS') ?></span>
      <span class="role-chip <?= $chip_cls ?>">
        <span class="material-icons" style="font-size:14px"><?= $role_icon ?></span>
        <?= $role_label ?>
      </span>
      <a href="<?= base_url('profil') ?>" class="topbar-user">
        <span class="material-icons">account_circle</span>
        <span class="uname"><?= htmlspecialchars($user->nama ?? '') ?></span>
      </a>
      <a href="<?= base_url('auth/logout') ?>" class="topbar-logout" title="Logout">
        <span class="material-icons">logout</span>
      </a>
    </header>

    <main class="content" id="mainContent">
      <?php
      $sukses = $this->session->flashdata('sukses');
      $error  = $this->session->flashdata('error');
      if ($sukses): ?><div class="flash flash-sukses"><span class="material-icons">check_circle</span><?= $sukses ?></div><?php endif;
      if ($error):  ?><div class="flash flash-error"><span class="material-icons">error</span><?= $error ?></div><?php endif;
      ?>
