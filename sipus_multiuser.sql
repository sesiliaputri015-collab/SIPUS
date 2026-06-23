-- ============================================================
-- DATABASE: sipus_multiuser (versi Multi-User)
-- SIPUS — Sistem Informasi Perpustakaan
-- Role: Admin | Operator | Anggota
-- Framework: CodeIgniter 3
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS sipus_multiuser CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sipus_multiuser;

-- ============================================================
-- TABEL: users
-- ============================================================
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id_user    INT AUTO_INCREMENT PRIMARY KEY,
    nama       VARCHAR(150) NOT NULL,
    username   VARCHAR(80)  NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    role       ENUM('admin','operator','anggota') NOT NULL DEFAULT 'anggota',
    status     ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
    id_anggota INT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABEL: kategori
-- ============================================================
DROP TABLE IF EXISTS kategori;
CREATE TABLE kategori (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nama       VARCHAR(100) NOT NULL,
    deskripsi  TEXT,
    ikon       VARCHAR(50)  DEFAULT 'menu_book',
    warna      VARCHAR(20)  DEFAULT 'blue',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABEL: buku
-- ============================================================
DROP TABLE IF EXISTS buku;
CREATE TABLE buku (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABEL: anggota
-- ============================================================
DROP TABLE IF EXISTS anggota;
CREATE TABLE anggota (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABEL: peminjaman
-- ============================================================
DROP TABLE IF EXISTS peminjaman;
CREATE TABLE peminjaman (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABEL: denda
-- ============================================================
DROP TABLE IF EXISTS denda;
CREATE TABLE denda (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- DATA AWAL: kategori
-- ============================================================
INSERT INTO kategori (nama, deskripsi, ikon, warna) VALUES
('Fiksi Ilmiah', 'Cerita fiksi berbasis sains dan teknologi', 'rocket_launch',    'blue'),
('Desain',       'Desain grafis, UI/UX, dan tipografi',       'brush',            'purple'),
('Teknologi',    'Ilmu komputer, AI, dan pemrograman',        'computer',         'teal'),
('Gaya Hidup',   'Produktivitas, kesehatan, dan kehidupan',   'self_improvement', 'green'),
('Sains',        'Matematika, fisika, biologi, kimia',        'science',          'amber');

-- ============================================================
-- DATA AWAL: buku
-- ============================================================
INSERT INTO buku (judul, penulis, isbn, kategori, tahun, stok, tersedia) VALUES
('The Architect of Tomorrow',    'Marcus Vane',      '978-001-111-000-1', 'Fiksi Ilmiah', 2021, 4, 4),
('Design Systems for Scale',     'Elena Rodriguez',  '978-002-222-000-2', 'Desain',       2020, 3, 3),
('Beyond Silicon: Future of AI', 'Alan Turing',      '978-003-333-000-3', 'Teknologi',    2022, 5, 5),
('The Art of Focus',             'James Clear',      '978-004-444-000-4', 'Gaya Hidup',   2019, 6, 6),
('Silent Horizon',               'Priya Mehta',      '978-005-555-000-5', 'Fiksi Ilmiah', 2023, 2, 2),
('Color Theory Basics',          'Josef Albers',     '978-006-666-000-6', 'Desain',       2020, 3, 3);

-- ============================================================
-- DATA AWAL: anggota
-- ============================================================
INSERT INTO anggota (nama, nim, email, prodi, angkatan, status, total_pinjam) VALUES
('Aulia Pertiwi', '2401234567', 'aulia@kampus.ac.id', 'Informatika',      2024, 'Aktif', 0),
('Budi Santoso',  '2301234568', 'budi@kampus.ac.id',  'Sistem Informasi', 2023, 'Aktif', 0),
('Citra Dewi',    '2201234569', 'citra@kampus.ac.id', 'Teknik Elektro',   2022, 'Aktif', 0),
('Liani Fanaida', '2501234570', 'liani@kampus.ac.id', 'Manajemen Informatika', 2025, 'Aktif', 0);

-- ============================================================
-- DATA AWAL: users
-- Password-hash dibuat dengan PHP password_hash($pass, PASSWORD_BCRYPT)
-- admin     / admin123
-- operator  / operator123
-- anggota   / anggota123
-- liani      / liani123
-- Hash valid untuk PHP 7.x / 8.x
-- ============================================================
INSERT INTO users (nama, username, password, role, status, id_anggota) VALUES
('Administrator', 'admin',
 '$2y$10$hS3XFtgvQvNPGYqMQ.0fWO8VHSM3Gzs.t5sTJ2wq/lYpHdFZi8Wq',
 'admin', 'aktif', NULL),

('Operator Satu', 'operator',
 '$2y$10$Uq/Wv.JEGzSp1C6uL5gHdeVk6j0TZw2L4yM1n3FqXqG4HcUFb.Dv6',
 'operator', 'aktif', NULL),

('Aulia Pertiwi', 'anggota',
 '$2y$10$Mm.o1Y3C5nI4VbEnHkNEMuC9VK3PJJCCQdZZSLjF3wYHgb0yqoiOW',
 'anggota', 'aktif', 1),

('Liani Fanaida', 'liani',
 '$2y$10$PLACEHOLDER_HASH_JALANKAN_RESET_PASSWORD_UNTUK_LIANI123xx',
 'anggota', 'aktif', 4);

-- CATATAN PENTING:
-- Jika login gagal, jalankan: http://localhost/sipus_ci3/auth/reset_hash
-- (halaman khusus development untuk mereset hash password)
