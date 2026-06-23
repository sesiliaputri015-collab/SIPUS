<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="page-header">
  <div class="page-icon" style="background:linear-gradient(135deg,#c62828,#ef5350)"><span
      class="material-icons">receipt_long</span></div>
  <div>
    <h1>
      <?= $title ?>
    </h1>
    <p>
      <?= count($denda) ?> catatan denda
    </p>
  </div>
  <?php if (in_array($role, ['admin', 'operator'])): ?>
    <div class="spacer"></div>
    <a href="<?= base_url('denda/tambah') ?>" class="btn btn-primary"><span class="material-icons">add</span> Tambah
      Denda</a>
  <?php endif; ?>
</div>

<?php if (in_array($role, ['admin', 'operator']) && isset($search)): ?>
  <div class="card" style="margin-bottom:1rem">
    <div class="card-body" style="padding:.875rem 1.25rem">
      <form method="GET" action="<?= base_url('denda') ?>">
        <div class="search-bar">
          <div class="search-input-wrap"><span class="material-icons">search</span>
            <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>"
              placeholder="Cari nama anggota atau keterangan...">
          </div>
          <button type="submit" class="btn btn-primary btn-sm"><span class="material-icons">search</span> Cari</button>
          <a href="<?= base_url('denda') ?>" class="btn btn-secondary btn-sm"><span
              class="material-icons">refresh</span></a>
        </div>
      </form>
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
          <th>Tanggal</th>
          <th>Keterangan</th>
          <th>Jumlah</th>
          <th>Status</th>
          <?php if (in_array($role, ['admin', 'operator'])): ?>
            <th>Aksi</th>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($denda)): ?>
          <tr>
            <td colspan="7">
              <div class="empty-state"><span class="material-icons">check_circle_outline</span>
                <p>Tidak ada data denda
                  <?= $role === 'anggota' ? ' untuk Anda' : '' ?>
                </p>
              </div>
            </td>
          </tr>
        <?php else: ?>
          <?php $no = 1;
          $total = 0;
          foreach ($denda as $d):
            $total += $d->jumlah; ?>
            <tr>
              <td style="color:#94a3b8">
                <?= $no++ ?>
              </td>
              <td>
                <div style="font-weight:600">
                  <?= htmlspecialchars($d->nama_anggota) ?>
                </div>
                <div style="font-size:.75rem;color:#94a3b8">
                  <?= $d->nim ?>
                </div>
              </td>
              <td style="font-size:.8375rem">
                <?= date('d/m/Y', strtotime($d->tanggal)) ?>
              </td>
              <td style="max-width:220px;font-size:.8375rem">
                <?= htmlspecialchars($d->keterangan) ?: '—' ?>
              </td>
              <td style="font-weight:700;<?= $d->status === 'Belum Dibayar' ? 'color:#dc2626' : '' ?>">
                Rp
                <?= number_format($d->jumlah, 0, ',', '.') ?>
              </td>
              <td>
                <span class="badge <?= $d->status === 'Lunas' ? 'badge-lunas' : 'badge-belum' ?>">
                  <?= $d->status === 'Lunas' ? '✓ Lunas' : '✗ Belum Bayar' ?>
                </span>
              </td>
              <?php if (in_array($role, ['admin', 'operator'])): ?>
                <td>
                  <?php if ($d->status === 'Belum Dibayar'): ?>
                    <a href="<?= base_url('denda/bayar/' . $d->id) ?>" class="btn btn-success btn-sm"
                      onclick="return confirm('Tandai lunas?')"><span class="material-icons">check</span> Lunas</a>
                  <?php endif; ?>
                  <a href="<?= base_url('denda/edit/' . $d->id) ?>" class="btn btn-secondary btn-sm btn-icon"><span
                      class="material-icons">edit</span></a>
                  <?php if ($role === 'admin'): ?>
                    <a href="<?= base_url('denda/hapus/' . $d->id) ?>" class="btn btn-danger btn-sm btn-icon"
                      onclick="return confirm('Hapus data denda ini?')"><span class="material-icons">delete</span></a>
                  <?php endif; ?>
                </td>
              <?php endif; ?>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>

      <?php if (!empty($denda)): ?>
        <tfoot>
          <tr style="background:#f8fafc;font-weight:700">
            <td colspan="4" style="padding:.75rem 1rem;text-align:right;color:#64748b">Total:</td>
            <td style="padding:.75rem 1rem;color:#dc2626">Rp
              <?= number_format($total, 0, ',', '.') ?>
            </td>
            <td colspan="<?= in_array($role, ['admin', 'operator']) ? '2' : '1' ?>"></td>
          </tr>
        </tfoot>
      <?php endif; ?>
    </table>
  </div>
</div>

<?php if ($role === 'anggota'): ?>
  <div
    style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:1rem;margin-top:1rem;font-size:.875rem;color:#dc2626">
    <span class="material-icons" style="vertical-align:middle;font-size:18px">info</span>
    Untuk melunasi denda, silakan datang langsung ke meja perpustakaan dengan membawa kartu anggota.
  </div>
<?php endif; ?>