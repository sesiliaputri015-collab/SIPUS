<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-header">
  <div class="page-icon" style="background:linear-gradient(135deg,#312e81,#4f46e5)"><span class="material-icons">add_circle</span></div>
  <div><h1>Pinjam Buku</h1><p>Pilih buku yang ingin dipinjam. Masa pinjam 7 hari.</p></div>
  <div class="spacer"></div>
  <a href="<?= base_url('peminjaman') ?>" class="btn btn-secondary"><span class="material-icons">arrow_back</span> Kembali</a>
</div>

<div class="card" style="margin-bottom:1rem"><div class="card-body" style="padding:.875rem 1.25rem">
  <form method="GET" action="<?= base_url('peminjaman/ajukan') ?>"><div class="search-bar">
    <div class="search-input-wrap"><span class="material-icons">search</span>
      <input type="text" name="search" value="<?= htmlspecialchars($search??'') ?>" placeholder="Cari judul atau penulis buku...">
    </div>
    <button type="submit" class="btn btn-primary btn-sm"><span class="material-icons">search</span> Cari</button>
    <a href="<?= base_url('peminjaman/ajukan') ?>" class="btn btn-secondary btn-sm"><span class="material-icons">refresh</span></a>
  </div></form>
</div></div>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1rem">
<?php if(empty($buku)): ?>
<div class="card" style="grid-column:1/-1"><div class="empty-state"><span class="material-icons">search_off</span><p>Tidak ada buku tersedia</p></div></div>
<?php else: foreach($buku as $b): ?>
<div class="card" style="padding:1.25rem;transition:transform .15s" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='none'">
  <div style="display:flex;gap:.875rem;align-items:flex-start">
    <div style="width:44px;height:60px;background:linear-gradient(135deg,#312e81,#4f46e5);border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
      <span class="material-icons" style="color:#fff;font-size:24px">menu_book</span>
    </div>
    <div style="flex:1;min-width:0">
      <div style="font-weight:700;font-size:.9375rem;line-height:1.3;margin-bottom:.25rem"><?= htmlspecialchars($b->judul) ?></div>
      <div style="font-size:.8125rem;color:#64748b;margin-bottom:.5rem"><?= htmlspecialchars($b->penulis) ?></div>
      <?php if($b->kategori): ?><span class="badge badge-dipinjam" style="font-size:.7rem"><?= htmlspecialchars($b->kategori) ?></span><?php endif; ?>
    </div>
  </div>
  <div style="display:flex;align-items:center;justify-content:space-between;margin-top:1rem;padding-top:.75rem;border-top:1px solid #f1f5f9">
    <span class="badge badge-dikembalikan"><span class="material-icons" style="font-size:11px">inventory_2</span><?= $b->tersedia ?> tersedia</span>
    <a href="<?= base_url('peminjaman/kirim_ajuan/'.$b->id) ?>" class="btn btn-primary btn-sm" onclick="return confirm('Ajukan peminjaman buku:\n<?= addslashes($b->judul) ?>\n\nMasa pinjam: 7 hari. Lanjutkan?')">
      <span class="material-icons">add_circle</span> Pinjam
    </a>
  </div>
</div>
<?php endforeach; endif; ?>
</div>
