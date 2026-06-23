<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — SIPUS Perpustakaan</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Inter',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#0f2744 0%,#1565c0 50%,#0d3b6e 100%);padding:1rem}
.login-wrap{width:100%;max-width:420px}
.login-brand{text-align:center;margin-bottom:2rem}
.login-brand .icon{width:64px;height:64px;background:rgba(255,255,255,.15);border-radius:20px;display:flex;align-items:center;justify-content:center;margin:0 auto .875rem;backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,.2)}
.login-brand .icon .material-icons{font-size:36px;color:#fff}
.login-brand h1{font-size:1.625rem;font-weight:800;color:#fff;letter-spacing:-.02em}
.login-brand p{font-size:.875rem;color:rgba(255,255,255,.65);margin-top:.3rem}
.login-card{background:#fff;border-radius:20px;padding:2rem;box-shadow:0 20px 60px rgba(0,0,0,.3)}
.login-card h2{font-size:1.125rem;font-weight:700;color:#1e293b;margin-bottom:.25rem}
.login-card .sub{font-size:.8375rem;color:#64748b;margin-bottom:1.5rem}
.form-group{margin-bottom:1.125rem}
.form-group label{display:block;font-size:.8125rem;font-weight:600;color:#374151;margin-bottom:.4rem}
.input-wrap{position:relative}
.input-wrap .material-icons{position:absolute;left:.875rem;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:20px;pointer-events:none}
.input-wrap input{width:100%;padding:.7rem .875rem .7rem 2.75rem;border:1.5px solid #e2e8f0;border-radius:10px;font-family:'Inter',sans-serif;font-size:.9375rem;color:#1e293b;background:#f8fafc;transition:all .2s}
.input-wrap input:focus{outline:none;border-color:#1565c0;background:#fff;box-shadow:0 0 0 3px rgba(21,101,192,.1)}
.btn-login{width:100%;padding:.8rem;background:linear-gradient(135deg,#1565c0,#1976d2);color:#fff;border:none;border-radius:10px;font-family:'Inter',sans-serif;font-size:.9375rem;font-weight:600;cursor:pointer;transition:all .2s;display:flex;align-items:center;justify-content:center;gap:.5rem;margin-top:.5rem}
.btn-login:hover{background:linear-gradient(135deg,#0d47a1,#1565c0);transform:translateY(-1px);box-shadow:0 8px 20px rgba(21,101,192,.35)}
.btn-login:active{transform:none}
.btn-login .material-icons{font-size:20px}
.flash{padding:.75rem 1rem;border-radius:10px;margin-bottom:1rem;display:flex;align-items:center;gap:.5rem;font-size:.8375rem}
.flash-error{background:#fef2f2;border:1px solid #fca5a5;color:#dc2626}
.flash .material-icons{font-size:18px;flex-shrink:0}
.demo-accounts{margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid #f1f5f9}
.demo-accounts h3{font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.875rem;text-align:center}
.demo-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:.5rem}
.demo-btn{background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px;padding:.75rem .5rem;text-align:center;cursor:pointer;transition:all .15s;text-decoration:none}
.demo-btn:hover{background:#eff6ff;border-color:#bfdbfe}
.demo-btn .badge{display:inline-block;padding:.2rem .5rem;border-radius:20px;font-size:.65rem;font-weight:700;margin-bottom:.4rem}
.demo-btn .uname{display:block;font-size:.75rem;font-weight:600;color:#374151}
.demo-btn .pass{display:block;font-size:.7rem;color:#94a3b8;font-family:monospace}
.badge-admin{background:#fef3c7;color:#d97706}
.badge-operator{background:#dbeafe;color:#1d4ed8}
.badge-anggota{background:#ede9fe;color:#6d28d9}
.login-footer{text-align:center;margin-top:1.5rem;font-size:.8rem;color:rgba(255,255,255,.5)}
@media(max-width:440px){
  .login-card{padding:1.5rem}
  .demo-grid{grid-template-columns:1fr}
}
</style>
</head>
<body>
<div class="login-wrap">
  <div class="login-brand">
    <div class="icon"><span class="material-icons">local_library</span></div>
    <h1>SIPUS</h1>
    <p>Sistem Informasi Perpustakaan</p>
  </div>

  <div class="login-card">
    <h2>Masuk ke Akun</h2>
    <p class="sub">Silakan masuk dengan kredensial Anda</p>

    <?php $error = $this->session->flashdata('error'); if ($error): ?>
    <div class="flash flash-error"><span class="material-icons">error</span><?= $error ?></div>
    <?php endif; ?>

    <form action="<?= base_url('auth/proses_login') ?>" method="POST">
      <div class="form-group">
        <label>Username</label>
        <div class="input-wrap">
          <span class="material-icons">person</span>
          <input type="text" name="username" placeholder="Masukkan username" required autofocus autocomplete="username">
        </div>
      </div>
      <div class="form-group">
        <label>Password</label>
        <div class="input-wrap">
          <span class="material-icons">lock</span>
          <input type="password" name="password" placeholder="Masukkan password" required autocomplete="current-password">
        </div>
      </div>
      <button type="submit" class="btn-login">
        <span class="material-icons">login</span> Masuk
      </button>
    </form>

    <div class="demo-accounts">
      <h3>Akun Demo — Klik untuk mengisi otomatis</h3>
      <div class="demo-grid">
        <a href="#" class="demo-btn" onclick="fillLogin('admin','admin123');return false">
          <span class="badge badge-admin">Admin</span>
          <span class="uname">admin</span>
          <span class="pass">admin123</span>
        </a>
        <a href="#" class="demo-btn" onclick="fillLogin('operator','operator123');return false">
          <span class="badge badge-operator">Operator</span>
          <span class="uname">operator</span>
          <span class="pass">operator123</span>
        </a>
        <a href="#" class="demo-btn" onclick="fillLogin('liani','liani123');return false">
          <span class="badge badge-anggota">Anggota</span>
          <span class="uname">liani</span>
          <span class="pass">liani123</span>
        </a>
      </div>
    </div>
  </div>

  <div class="login-footer">SIPUS &copy; <?= date('Y') ?> &mdash; Sistem Informasi Perpustakaan</div>
</div>
<script>
function fillLogin(u,p){
  document.querySelector('input[name="username"]').value=u;
  document.querySelector('input[name="password"]').value=p;
  document.querySelector('input[name="username"]').focus();
}
</script>
</body>
</html>
