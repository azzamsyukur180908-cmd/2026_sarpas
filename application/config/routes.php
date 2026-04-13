<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| DEFAULT ROUTE
| -------------------------------------------------------------------------
*/
// Saat aplikasi dibuka pertama kali, arahkan ke halaman login
$config['base_url'] = 'http://localhost/spmb_smk/'; // Sesuaikan dengan folder project Anda
$route['default_controller'] = 'auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/*
| -------------------------------------------------------------------------
| AUTHENTICATION ROUTES
| -------------------------------------------------------------------------
*/
$route['login'] = 'auth/index';
$route['logout'] = 'auth/logout';
$route['proses_login'] = 'auth/login_aksi';

/*
| -------------------------------------------------------------------------
| PANITIA ROUTES (Akses CRUD & Kelola)
| -------------------------------------------------------------------------
*/
// Dashboard & Pengunjung
$route['panitia/pengunjung'] = 'pengunjung';
$route['panitia/pengunjung/simpan'] = 'panitia/simpan_pengunjung';

// Kelola Pendaftar & AJAX Progress
$route['panitia/pendaftar'] = 'panitia/data_pendaftar';
$route['panitia/update_progres'] = 'panitia/update_tahapan'; // AJAX call

// Checklist & Penilaian
$route['panitia/checklist/(:num)'] = 'panitia/view_checklist/$1';
$route['panitia/input_nilai'] = 'panitia/simpan_nilai';

/*
| -------------------------------------------------------------------------
| KEPALA SEKOLAH ROUTES (Akses Laporan & Rekap)
| -------------------------------------------------------------------------
*/
$route['kepsek'] = 'kepsek/dashboard'; // URL: domain.com/kepsek
$route['kepsek/rekap'] = 'kepsek/halaman_rekap';
$route['kepsek/cetak_laporan'] = 'kepsek/cetak';

$route['checklist'] = 'checklist/index';
$route['checklist/update_status'] = 'checklist/update_status';