<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="<?= base_url('dashboard') ?>" class="brand-link">
            <img src="https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png" alt="SIBERES Logo" class="brand-image opacity-75 shadow" />
            <span class="brand-text fw-light fw-bold">SIBERES App</span>
        </a>
    </div>
    
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" data-accordion="false">
                
                <li class="nav-item">
                    <a href="<?= base_url('dashboard') ?>" class="nav-link <?= ($this->uri->segment(1) == 'dashboard' || $this->uri->segment(1) == '') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                
                <?php if($this->session->userdata('level') == 'admin'): ?>
                <li class="nav-header mt-3 text-uppercase text-secondary fs-7 fw-bold">Administrator</li>
                
                <li class="nav-item">
                    <a href="<?= base_url('sarana') ?>" class="nav-link <?= ($this->uri->segment(1) == 'sarana') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-box-seam-fill"></i>
                        <p>Data Master Sarana</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="<?= base_url('laporan') ?>" class="nav-link <?= ($this->uri->segment(1) == 'laporan') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-clipboard-data-fill"></i>
                        <p>Rekap Seluruh Laporan</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('users') ?>" class="nav-link <?= ($this->uri->segment(1) == 'users') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-people-fill"></i>
                        <p>Manajemen User</p>
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-header mt-3 text-uppercase text-secondary fs-7 fw-bold">Menu Pengaduan</li>
                
                <li class="nav-item">
                    <a href="<?= base_url('pengaduan/tambah') ?>" class="nav-link <?= ($this->uri->segment(2) == 'tambah') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-pencil-square"></i>
                        <p>Buat Laporan Baru</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="<?= base_url('pengaduan') ?>" class="nav-link <?= ($this->uri->segment(1) == 'pengaduan' && $this->uri->segment(2) == '') ? 'active' : '' ?>">
                        <i class="nav-icon bi bi-clock-history"></i>
                        <p>Riwayat Laporan Saya</p>
                    </a>
                </li>
                </ul>
        </nav>
    </div>
</aside>
