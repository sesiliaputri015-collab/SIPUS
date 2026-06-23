<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'auth/login';
$route['404_override']       = '';
$route['translate_uri_dashes'] = FALSE;

// Auth
$route['auth/login']        = 'Auth/login';
$route['auth/proses_login'] = 'Auth/proses_login';
$route['auth/logout']       = 'Auth/logout';
$route['auth/reset_hash']   = 'Auth/reset_hash';

// Dashboard
$route['dashboard']         = 'Dashboard/index';

// Profil
$route['profil']            = 'Profil/index';
$route['profil/update']     = 'Profil/update';

// User (admin only)
$route['user']              = 'User/index';
$route['user/tambah']       = 'User/tambah';
$route['user/simpan']       = 'User/simpan';
$route['user/edit/(:num)']  = 'User/edit/$1';
$route['user/update/(:num)']= 'User/update/$1';
$route['user/hapus/(:num)'] = 'User/hapus/$1';

// Buku
$route['buku']                  = 'Buku/index';
$route['buku/tambah']           = 'Buku/tambah';
$route['buku/simpan']           = 'Buku/simpan';
$route['buku/edit/(:num)']      = 'Buku/edit/$1';
$route['buku/update/(:num)']    = 'Buku/update/$1';
$route['buku/hapus/(:num)']     = 'Buku/hapus/$1';

// Kategori (admin only)
$route['kategori']              = 'Kategori/index';
$route['kategori/tambah']       = 'Kategori/tambah';
$route['kategori/simpan']       = 'Kategori/simpan';
$route['kategori/edit/(:num)']  = 'Kategori/edit/$1';
$route['kategori/update/(:num)']= 'Kategori/update/$1';
$route['kategori/hapus/(:num)'] = 'Kategori/hapus/$1';

// Anggota
$route['anggota']               = 'Anggota/index';
$route['anggota/tambah']        = 'Anggota/tambah';
$route['anggota/simpan']        = 'Anggota/simpan';
$route['anggota/edit/(:num)']   = 'Anggota/edit/$1';
$route['anggota/update/(:num)'] = 'Anggota/update/$1';
$route['anggota/hapus/(:num)']  = 'Anggota/hapus/$1';

// Peminjaman
$route['peminjaman']                        = 'Peminjaman/index';
$route['peminjaman/tambah']                 = 'Peminjaman/tambah';
$route['peminjaman/simpan']                 = 'Peminjaman/simpan';
$route['peminjaman/ajukan']                 = 'Peminjaman/ajukan';
$route['peminjaman/kirim_ajuan/(:num)']     = 'Peminjaman/kirim_ajuan/$1';
$route['peminjaman/ajukan_kembali/(:num)']  = 'Peminjaman/ajukan_kembali/$1';
$route['peminjaman/setujui/(:num)']         = 'Peminjaman/setujui/$1';
$route['peminjaman/tolak/(:num)']           = 'Peminjaman/tolak/$1';
$route['peminjaman/kembalikan/(:num)']      = 'Peminjaman/kembalikan/$1';
$route['peminjaman/hapus/(:num)']           = 'Peminjaman/hapus/$1';

// Denda
$route['denda']                 = 'Denda/index';
$route['denda/tambah']          = 'Denda/tambah';
$route['denda/simpan']          = 'Denda/simpan';
$route['denda/edit/(:num)']     = 'Denda/edit/$1';
$route['denda/update/(:num)']   = 'Denda/update/$1';
$route['denda/bayar/(:num)']    = 'Denda/bayar/$1';
$route['denda/hapus/(:num)']    = 'Denda/hapus/$1';
