<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Cetak Rekap Laporan</h1>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
      
    <div class="card card-secondary card-outline">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter"></i> Filter Laporan</h3>
        </div>
        <div class="card-body">
            <form action="<?= base_url('laporan') ?>" method="post">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Mulai Tanggal</label>
                            <input type="date" name="tgl_awal" class="form-control" value="<?= $tgl_awal ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Sampai Tanggal</label>
                            <input type="date" name="tgl_akhir" class="form-control" value="<?= $tgl_akhir ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Status Laporan</label>
                            <select name="status" class="form-control">
                                <option value="semua" <?= ($status == 'semua') ? 'selected' : '' ?>>Semua Status</option>
                                <option value="0" <?= ($status == '0') ? 'selected' : '' ?>>Menunggu Proses</option>
                                <option value="proses" <?= ($status == 'proses') ? 'selected' : '' ?>>Sedang Diperbaiki</option>
                                <option value="selesai" <?= ($status == 'selesai') ? 'selected' : '' ?>>Selesai</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label><br>
                            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Tampilkan Data</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-primary">
      <div class="card-body table-responsive">
        <table id="example1" class="table table-bordered table-striped table-sm text-sm">
          <thead class="bg-light">
            <tr>
              <th width="5%">No</th>
              <th width="10%">Tgl Lapor</th>
              <th width="15%">Pelapor</th>
              <th width="20%">Sarana / Lokasi</th>
              <th width="20%">Keluhan / Kerusakan</th>
              <th width="10%">Status</th>
              <th width="20%">Penyelesaian (Oleh Petugas)</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1; foreach($laporan as $l): ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= date('d-m-Y', strtotime($l->tgl_pengaduan)) ?></td>
                <td><?= $l->nama_pelapor ?></td>
                <td><b><?= $l->nama_sarana ?></b><br>Lokasi: <?= $l->lokasi ?></td>
                <td><?= $l->deskripsi_laporan ?></td>
                <td class="text-center">
                    <?php 
                        if($l->status == '0') echo '<span class="badge badge-danger">Menunggu</span>';
                        else if($l->status == 'proses') echo '<span class="badge badge-warning">Proses</span>';
                        else echo '<span class="badge badge-success">Selesai</span>';
                    ?>
                </td>
                <td>
                    <?php if($l->status == 'selesai'): ?>
                        <?= $l->tanggapan ?><br>
                        <small class="text-success font-weight-bold">
                            <i class="fas fa-check-circle"></i> Oleh: <?= $l->nama_petugas ?> (<?= date('d/m/y', strtotime($l->tgl_tanggapan)) ?>)
                        </small>
                    <?php else: ?>
                        <em class="text-muted">Belum ada penyelesaian</em>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</section>
