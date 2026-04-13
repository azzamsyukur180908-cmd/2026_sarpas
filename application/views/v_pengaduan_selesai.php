<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Laporan Selesai</h1>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <div class="card card-success card-outline">
      <div class="card-header">
        <h3 class="card-title">Daftar Laporan yang Telah Diselesaikan</h3>
      </div>
      
      <div class="card-body table-responsive">
        <table id="tabelPengaduanSelesai" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th width="5%">No</th>
              <th width="15%">Tanggal</th>
              <th width="20%">Pelapor</th>
              <th width="25%">Sarana & Lokasi</th>
              <th>Status</th>
              <th width="10%" class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody id="show_data">
            </tbody>
        </table>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="modalSelesaiDetail" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-check-double"></i> Arsip Detail Laporan</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body bg-light">
        <div class="row">
            
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h6 class="card-title font-weight-bold text-primary mb-0"><i class="fas fa-user"></i> Data Pelapor</h6>
                    </div>
                    <div class="card-body p-3">
                        <table class="table table-sm table-borderless mb-2">
                            <tr><th width="35%">Nama Pelapor</th><td>: <span id="dt_pelapor"></span></td></tr>
                            <tr><th>Tgl. Dilaporkan</th><td>: <span id="dt_tanggal"></span></td></tr>
                            <tr><th>Sarana</th><td>: <span id="dt_sarana" class="text-danger font-weight-bold"></span></td></tr>
                            <tr><th>Lokasi</th><td>: <span id="dt_lokasi"></span></td></tr>
                        </table>
                        <p class="font-weight-bold mb-1">Deskripsi Kerusakan:</p>
                        <div class="p-2 bg-light border rounded text-muted" id="dt_deskripsi" style="min-height: 60px;"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm border-success">
                    <div class="card-header bg-white">
                        <h6 class="card-title font-weight-bold text-success mb-0"><i class="fas fa-tools"></i> Data Penanganan</h6>
                    </div>
                    <div class="card-body p-3">
                        <table class="table table-sm table-borderless mb-2">
                            <tr><th width="35%">Ditangani Oleh</th><td>: <span id="dt_petugas" class="font-weight-bold text-success"></span></td></tr>
                            <tr><th>Tgl. Selesai</th><td>: <span id="dt_tgl_selesai"></span></td></tr>
                        </table>
                        <p class="font-weight-bold mb-1">Catatan Perbaikan:</p>
                        <div class="p-2 bg-success text-white rounded mb-3" id="dt_tanggapan" style="min-height: 60px;"></div>
                        
                        <p class="font-weight-bold mb-1 text-center">Foto Bukti Kerusakan Awal:</p>
                        <div class="text-center">
                            <img id="dt_foto" src="" class="img-fluid img-thumbnail shadow-sm" style="max-height: 200px;" alt="Foto Bukti">
                        </div>
                    </div>
                </div>
            </div>

        </div>
      </div>
      <div class="modal-footer bg-white">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup Arsip</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var interval = setInterval(function() {
        if (window.jQuery) {
            clearInterval(interval);
            jalankanScriptSelesai();
        }
    }, 100);
});

function jalankanScriptSelesai() {
    var table = $('#tabelPengaduanSelesai').DataTable({
        "responsive": true, "autoWidth": false,
    });

    // 1. TAMPIL DATA TABEL STATUS 'SELESAI'
    function tampil_data() {
        $.ajax({
            url: '<?= base_url("pengaduan/get_data_selesai") ?>',
            type: 'GET',
            dataType: 'JSON',
            success: function(data) {
                table.clear();
                $.each(data, function(i, item) {
                    
                    var badge = '<span class="badge badge-success"><i class="fas fa-check-double"></i> Selesai</span>';
                    var btnAction = '<button class="btn btn-info btn-sm item_detail" data-id="'+item.id_pengaduan+'"><i class="fas fa-search"></i> Lihat Arsip</button>';

                    table.row.add([
                        (i + 1),
                        item.tgl_pengaduan,
                        item.nama_pelapor,
                        '<b>' + item.nama_sarana + '</b><br><small>' + item.lokasi + '</small>',
                        badge,
                        btnAction
                    ]);
                });
                table.draw();
            }
        });
    }

    tampil_data();

    // 2. MUNCULKAN MODAL DETAIL ARSIP
    $('#tabelPengaduanSelesai').on('click', '.item_detail', function() {
        var id = $(this).attr('data-id');
        
        $.ajax({
            url: '<?= base_url("pengaduan/get_detail_selesai/") ?>' + id,
            type: 'GET',
            dataType: 'JSON',
            success: function(data) {
                // Ekstrak data JSON yang kita pecah di controller
                var laporan = data.laporan;
                var tanggapan = data.tanggapan;

                // Isi Data Pelapor
                $('#dt_pelapor').text(laporan.nama_pelapor);
                $('#dt_tanggal').text(laporan.tgl_pengaduan);
                $('#dt_sarana').text(laporan.nama_sarana);
                $('#dt_lokasi').text(laporan.lokasi);
                $('#dt_deskripsi').text(laporan.deskripsi_laporan);
                
                // Isi Data Penanganan (Tanggapan)
                if(tanggapan) {
                    $('#dt_petugas').text(tanggapan.nama_petugas);
                    $('#dt_tgl_selesai').text(tanggapan.tgl_tanggapan);
                    $('#dt_tanggapan').text(tanggapan.tanggapan);
                } else {
                    $('#dt_petugas').text('-');
                    $('#dt_tgl_selesai').text('-');
                    $('#dt_tanggapan').text('Belum ada catatan.');
                }
                
                // Cek Foto
                if(laporan.foto && laporan.foto != '') {
                    $('#dt_foto').attr('src', '<?= base_url("assets/img/pengaduan/") ?>' + laporan.foto);
                } else {
                    $('#dt_foto').attr('src', 'https://via.placeholder.com/300x200?text=Tidak+Ada+Foto');
                }
                
                $('#modalSelesaiDetail').modal('show');
            }
        });
    });
}
</script>
