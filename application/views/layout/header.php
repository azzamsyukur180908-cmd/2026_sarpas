<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SARPRAS | Pengaduan Sarana</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="<?php echo base_url('adminlte/plugins/fontawesome-free/css/all.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('adminlte/plugins/daterangepicker/daterangepicker.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('adminlte/dist/css/adminlte.min.css'); ?>">
    <style>
        .nav-header { color: #b8c7e0 !important; font-weight: bold; margin-top: 10px; margin-bottom: 5px; }
    </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link text-danger" href="<?= base_url('auth/logout') ?>">
          <i class="fas fa-sign-out-alt"></i> Logout
        </a>
      </li>
    </ul>
  </nav>

  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?= base_url('dashboard') ?>" class="brand-link">
      <span class="brand-text font-weight-light">SIBERES App</span>
    </a>

    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="#" class="d-block"><?= $this->session->userdata('nama') ?></a>
          <span class="badge badge-success"><?= ucfirst($this->session->userdata('level')) ?></span>
        </div>
      </div>

      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
          
          <li class="nav-item">
            <a href="<?= base_url('dashboard') ?>" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <?php if($this->session->userdata('level') == 'admin'): ?>
          <li class="nav-header">MASTER DATA</li>
          <li class="nav-item">
            <a href="<?= base_url('sarana') ?>" class="nav-link">
              <i class="nav-icon fas fa-boxes"></i>
              <p>Data Master Sarana</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('users') ?>" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>Manajemen User</p>
            </a>
          </li>
          <?php endif; ?>

          <?php if($this->session->userdata('level') == 'admin' || $this->session->userdata('level') == 'petugas'): ?>
          <li class="nav-header">MANAJEMEN LAPORAN</li>
          <li class="nav-item">
            <a href="<?= base_url('pengaduan/masuk') ?>" class="nav-link">
              <i class="nav-icon fas fa-envelope-open-text"></i>
              <p>Laporan Masuk</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('pengaduan/proses') ?>" class="nav-link">
              <i class="nav-icon fas fa-tools"></i>
              <p>Laporan Diproses</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('pengaduan/selesai') ?>" class="nav-link">
              <i class="nav-icon fas fa-check-double"></i>
              <p>Laporan Selesai</p>
            </a>
          </li>
          <?php endif; ?>
          
          <?php if($this->session->userdata('level') == 'admin'): ?>
          <li class="nav-header">REPORTING</li>
          <li class="nav-item">
            <a href="<?= base_url('laporan') ?>" class="nav-link">
              <i class="nav-icon fas fa-print"></i>
              <p>Cetak Rekap Laporan</p>
            </a>
          </li>
          <?php endif; ?>

          <?php if($this->session->userdata('level') == 'pelapor'): ?>
          <li class="nav-header">PENGADUAN SAYA</li>
          <li class="nav-item">
            <a href="<?= base_url('pengaduan/buat') ?>" class="nav-link">
              <i class="nav-icon fas fa-plus-square"></i>
              <p>Buat Laporan Baru</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('pengaduan/riwayat') ?>" class="nav-link">
              <i class="nav-icon fas fa-history"></i>
              <p>Riwayat Laporan Saya</p>
            </a>
          </li>
          <?php endif; ?>

        </ul>
      </nav>
    </div>
  </aside>

  <div class="content-wrapper">
