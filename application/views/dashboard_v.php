<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Dashboard SARPRAS</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            
            <div class="alert alert-primary shadow-sm border-0" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i> 
                Selamat datang kembali, <strong><?= ucfirst($this->session->userdata('nama')); ?></strong>! Anda login sebagai <strong><?= ucfirst($this->session->userdata('level')); ?></strong>.
            </div>

            <div class="row mt-4">
                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-primary shadow-sm">
                        <div class="inner">
                            <h3><?= $total_sarana; ?></h3>
                            <p>Total Sarana</p>
                        </div>
                        <i class="small-box-icon bi bi-box-seam-fill"></i>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-danger shadow-sm">
                        <div class="inner">
                            <h3><?= $laporan_baru; ?></h3>
                            <p>Laporan Baru Masuk</p>
                        </div>
                        <i class="small-box-icon bi bi-envelope-exclamation-fill"></i>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-warning shadow-sm">
                        <div class="inner">
                            <h3><?= $diproses; ?></h3>
                            <p>Sedang Diperbaiki</p>
                        </div>
                        <i class="small-box-icon bi bi-tools"></i>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6">
                    <div class="small-box text-bg-success shadow-sm">
                        <div class="inner">
                            <h3><?= $selesai; ?></h3>
                            <p>Selesai Diperbaiki</p>
                        </div>
                        <i class="small-box-icon bi bi-check-circle-fill"></i>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</main>
