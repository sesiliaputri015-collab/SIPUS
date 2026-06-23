<?php
/**
 * SIPUS — Setup Lengkap (One Click)
 * Buat database, tabel, data, dan password hash yang valid
 * HAPUS file ini setelah selesai!
 */

$host = 'localhost';
$user = 'root';
$pass = ''; // isi kalau MySQL kamu ada password
$dbname = 'sipus_multiuser';

$mysqli = new mysqli($host, $user, $pass);

if ($mysqli->connect_error) {
    die("Koneksi MySQL gagal: " . $mysqli->connect_error);
}

$steps = [];
$ok = true;

// =============================================
// STEP 1: Hapus database lama kalau ada
// =============================================
$mysqli->query("DROP DATABASE IF EXISTS `$dbname`");
$steps[] = ["Hapus database lama", true];

// =============================================
// STEP 2: Buat database baru
// =============================================
if (!$mysqli->query("CREATE DATABASE `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")) {
    $steps[] = ["Buat database", false, $mysqli->error];
    $ok = false;
} else {
    $steps[] = ["Buat database", true];
}
$mysqli->select_db($dbname);

// =============================================
// STEP 3: Buat semua tabel
// =============================================
$tables = [
    "users" => "CREATE TABLE users (
        id_user    INT AUTO_INCREMENT PRIMARY KEY,
        nama       VARCHAR(150) NOT NULL,
        username   VARCHAR(80)  NOT NULL UNIQUE,
        password   VARCHAR(255) NOT NULL,
        role       ENUM('admin','operator','anggota') NOT NULL DEFAULT 'anggota',
        status     ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
        id_anggota INT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    "kategori" => "CREATE TABLE kategori (
        id         INT AUTO_INCREMENT PRIMARY KEY,
        nama       VARCHAR(100) NOT NULL,
        deskripsi  TEXT,
        ikon       VARCHAR(50)  DEFAULT 'menu_book',
        warna      VARCHAR(20)  DEFAULT 'blue',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    "buku" => "CREATE TABLE buku (
        id         INT AUTO_INCREMENT PRIMARY KEY,
        judul      VARCHAR(200) NOT NULL,
        penulis    VARCHAR(150) NOT NULL,
        isbn       VARCHAR(30),
        kategori   VARCHAR(100),
        tahun      YEAR,
        stok       INT DEFAULT 1,
        tersedia   INT DEFAULT 1,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    "anggota" => "CREATE TABLE anggota (
        id           INT AUTO_INCREMENT PRIMARY KEY,
        nama         VARCHAR(150) NOT NULL,
        nim          VARCHAR(20)  NOT NULL UNIQUE,
        email        VARCHAR(100),
        prodi        VARCHAR(100),
        angkatan     YEAR,
        status       ENUM('Aktif','Tidak Aktif') DEFAULT 'Aktif',
        total_pinjam INT DEFAULT 0,
        created_at   DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at   DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    "peminjaman" => "CREATE TABLE peminjaman (
        id               INT AUTO_INCREMENT PRIMARY KEY,
        anggota_id       INT NOT NULL,
        buku_id          INT NOT NULL,
        tanggal_pinjam   DATE NOT NULL,
        tanggal_kembali  DATE NOT NULL,
        tanggal_aktual   DATE NULL,
        status           ENUM('Menunggu','Dipinjam','Menunggu Kembali','Terlambat','Dikembalikan') DEFAULT 'Menunggu',
        denda            INT DEFAULT 0,
        catatan          TEXT NULL,
        created_at       DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at       DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (anggota_id) REFERENCES anggota(id) ON DELETE CASCADE,
        FOREIGN KEY (buku_id)    REFERENCES buku(id)    ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    "denda" => "CREATE TABLE denda (
        id          INT AUTO_INCREMENT PRIMARY KEY,
        anggota_id  INT NOT NULL,
        pinjam_id   INT NULL,
        jumlah      INT DEFAULT 0,
        tanggal     DATE NOT NULL,
        status      ENUM('Belum Dibayar','Lunas') DEFAULT 'Belum Dibayar',
        keterangan  VARCHAR(200),
        created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at  DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (anggota_id) REFERENCES anggota(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    "ci_sessions" => "CREATE TABLE ci_sessions (
        id VARCHAR(128) NOT NULL,
        ip_address VARCHAR(45) NOT NULL,
        timestamp INT NOT NULL DEFAULT 0,
        data BLOB NOT NULL,
        PRIMARY KEY (id),
        KEY ci_sessions_timestamp (timestamp)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
];

foreach ($tables as $name => $sql) {
    $r = $mysqli->query($sql);
    $steps[] = ["Tabel: $name", $r, $r ? null : $mysqli->error];
    if (!$r)
        $ok = false;
}

// =============================================
// STEP 4: Insert data kategori
// =============================================
$r = $mysqli->query("INSERT INTO kategori (nama, deskripsi, ikon, warna) VALUES
    ('Fiksi Ilmiah', 'Cerita fiksi berbasis sains dan teknologi', 'rocket_launch', 'blue'),
    ('Desain', 'Desain grafis, UI/UX, dan tipografi', 'brush', 'purple'),
    ('Teknologi', 'Ilmu komputer, AI, dan pemrograman', 'computer', 'teal'),
    ('Gaya Hidup', 'Produktivitas, kesehatan, dan kehidupan', 'self_improvement', 'green'),
    ('Sains', 'Matematika, fisika, biologi, kimia', 'science', 'amber')
");
$steps[] = ["Data kategori", $r, $r ? null : $mysqli->error];
if (!$r)
    $ok = false;

// =============================================
// STEP 5: Insert data buku
// =============================================
$r = $mysqli->query("INSERT INTO buku (judul, penulis, isbn, kategori, tahun, stok, tersedia) VALUES
    ('The Architect of Tomorrow', 'Marcus Vane', '978-001-111-000-1', 'Fiksi Ilmiah', 2021, 4, 4),
    ('Design Systems for Scale', 'Elena Rodriguez', '978-002-222-000-2', 'Desain', 2020, 3, 3),
    ('Beyond Silicon: Future of AI', 'Alan Turing', '978-003-333-000-3', 'Teknologi', 2022, 5, 5),
    ('The Art of Focus', 'James Clear', '978-004-444-000-4', 'Gaya Hidup', 2019, 6, 6),
    ('Silent Horizon', 'Priya Mehta', '978-005-555-000-5', 'Fiksi Ilmiah', 2023, 2, 2),
    ('Color Theory Basics', 'Josef Albers', '978-006-666-000-6', 'Desain', 2020, 3, 3)
");
$steps[] = ["Data buku", $r, $r ? null : $mysqli->error];
if (!$r)
    $ok = false;

// =============================================
// STEP 6: Insert data anggota
// =============================================
$r = $mysqli->query("INSERT INTO anggota (nama, nim, email, prodi, angkatan, status, total_pinjam) VALUES
    ('Aulia Pertiwi', '2401234567', 'aulia@kampus.ac.id', 'Informatika', 2024, 'Aktif', 0),
    ('Budi Santoso', '2301234568', 'budi@kampus.ac.id', 'Sistem Informasi', 2023, 'Aktif', 0),
    ('Citra Dewi', '2201234569', 'citra@kampus.ac.id', 'Teknik Elektro', 2022, 'Aktif', 0),
    ('Liani Fanaida', '2501234570', 'liani@kampus.ac.id', 'Manajemen Informatika', 2025, 'Aktif', 0)
");
$steps[] = ["Data anggota", $r, $r ? null : $mysqli->error];
if (!$r)
    $ok = false;

// =============================================
// STEP 7: Insert users dengan HASH YANG BENAR
// =============================================
// password_hash() dijalankan langsung oleh PHP — 100% valid
$users = [
    ['Administrator', 'admin', 'admin123', 'admin', null],
    ['Operator Satu', 'operator', 'operator123', 'operator', null],
    ['Aulia Pertiwi', 'anggota', 'anggota123', 'anggota', 1],
    ['Liani Fanaida', 'liani', 'liani123', 'anggota', 4],
];

$user_results = [];
foreach ($users as $u) {
    $hash = password_hash($u[2], PASSWORD_BCRYPT);
    $stmt = $mysqli->prepare("INSERT INTO users (nama, username, password, role, status, id_anggota) VALUES (?, ?, ?, ?, 'aktif', ?)");
    $stmt->bind_param('ssssi', $u[0], $u[1], $hash, $u[3], $u[4]);
    $r = $stmt->execute();
    $user_results[] = [
        'nama' => $u[0],
        'username' => $u[1],
        'password' => $u[2],
        'hash' => $hash,
        'hash_len' => strlen($hash),
        'ok' => $r,
    ];
    $steps[] = ["User: {$u[1]}", $r, $r ? null : $stmt->error];
    if (!$r)
        $ok = false;
    $stmt->close();
}

$mysqli->close();

// =============================================
// TAMPILKAN HASIL
// =============================================
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>SIPUS Setup</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: #0f172a;
            color: #e2e8f0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .box {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 16px;
            padding: 32px;
            width: 640px;
            max-width: 100%;
        }

        h1 {
            font-size: 22px;
            margin-bottom: 4px;
        }

        .sub {
            color: #64748b;
            font-size: 13px;
            margin-bottom: 24px;
        }

        .log {
            margin-bottom: 24px;
        }

        .log-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 7px 0;
            font-size: 13px;
            border-bottom: 1px solid #1e293b;
        }

        .log-icon {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .log-icon.ok {
            background: rgba(16, 185, 129, .15);
            color: #34d399;
        }

        .log-icon.fail {
            background: rgba(239, 68, 68, .15);
            color: #f87171;
        }

        .log-err {
            color: #f87171;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 13px;
        }

        th {
            text-align: left;
            padding: 8px 10px;
            background: #334155;
            color: #94a3b8;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        td {
            padding: 8px 10px;
            border-bottom: 1px solid #334155;
        }

        .badge {
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge.admin {
            background: rgba(79, 70, 229, .15);
            color: #818cf8;
        }

        .badge.operator {
            background: rgba(16, 185, 129, .15);
            color: #34d399;
        }

        .badge.anggota {
            background: rgba(59, 130, 246, .15);
            color: #60a5fa;
        }

        .hash {
            font-family: 'Consolas', monospace;
            font-size: 10px;
            color: #64748b;
            word-break: break-all;
        }

        .hash-len {
            font-weight: 700;
        }

        .hash-len.ok {
            color: #34d399;
        }

        .hash-len.bad {
            color: #f87171;
        }

        .success-box {
            background: rgba(16, 185, 129, .08);
            border: 1px solid rgba(16, 185, 129, .2);
            border-radius: 12px;
            padding: 16px 20px;
            margin-top: 20px;
        }

        .success-box h3 {
            color: #34d399;
            font-size: 15px;
            margin-bottom: 8px;
        }

        .success-box p {
            font-size: 13px;
            color: #94a3b8;
            line-height: 1.7;
        }

        .success-box code {
            background: #0f172a;
            padding: 2px 8px;
            border-radius: 4px;
            color: #e2e8f0;
            font-size: 12px;
        }

        .error-box {
            background: rgba(239, 68, 68, .08);
            border: 1px solid rgba(239, 68, 68, .2);
            border-radius: 12px;
            padding: 16px 20px;
            margin-top: 20px;
        }

        .error-box h3 {
            color: #f87171;
            font-size: 15px;
        }

        .warn {
            color: #fbbf24;
            font-size: 12px;
            margin-top: 16px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="box">
        <h1>SIPUS Setup</h1>
        <p class="sub">Database:
            <?php echo $dbname; ?> &middot; Server:
            <?php echo $host; ?>
        </p>

        <div class="log">
            <?php foreach ($steps as $s): ?>
                <div class="log-row">
                    <div class="log-icon <?php echo $s[1] ? 'ok' : 'fail'; ?>">
                        <?php echo $s[1] ? '&#10003;' : '&#10007;'; ?>
                    </div>
                    <span>
                        <?php echo $s[0]; ?>
                    </span>
                    <?php if (!$s[1] && isset($s[2])): ?>
                        <span class="log-err">
                            <?php echo htmlspecialchars($s[2]); ?>
                        </span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <table>
            <tr>
                <th>Nama</th>
                <th>Username</th>
                <th>Password</th>
                <th>Role</th>
                <th>Hash</th>
                <th>Len</th>
            </tr>
            <?php foreach ($user_results as $u): ?>
                <tr>
                    <td>
                        <?php echo $u['nama']; ?>
                    </td>
                    <td><code><?php echo $u['username']; ?></code></td>
                    <td><code><?php echo $u['password']; ?></code></td>
                    <td><span
                            class="badge <?php echo $u['nama'] === 'Administrator' ? 'admin' : ($u['nama'] === 'Operator Satu' ? 'operator' : 'anggota'); ?>">
                            <?php echo $u['nama'] === 'Administrator' ? 'admin' : ($u['nama'] === 'Operator Satu' ? 'operator' : 'anggota'); ?>
                        </span></td>
                    <td class="hash">
                        <?php echo $u['hash']; ?>
                    </td>
                    <td><span class="hash-len <?php echo $u['hash_len'] === 60 ? 'ok' : 'bad'; ?>">
                            <?php echo $u['hash_len']; ?>
                        </span></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <?php if ($ok): ?>
            <div class="success-box">
                <h3>Semua Berhasil!</h3>
                <p>
                    Login di: <code>http://localhost/sipus_ci3/</code><br><br>
                    Admin: <code>admin</code> / <code>admin123</code><br>
                    Operator: <code>operator</code> / <code>operator123</code><br>
                    Anggota: <code>anggota</code> / <code>anggota123</code><br>
                    Liani: <code>liani</code> / <code>liani123</code>
                </p>
            </div>
        <?php else: ?>
            <div class="error-box">
                <h3>Ada Yang Gagal</h3>
                <p>Periksa log merah di atas. Pastikan MySQL berjalan dan user root tidak ada password.</p>
            </div>
        <?php endif; ?>

        <p class="warn">Setelah berhasil, HAPUS file setup.php ini dari server!</p>
    </div>
</body>

</html>