<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Laporan Diproses</h1>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <div class="card card-warning card-outline">
      <div class="card-header">
        <h3 class="card-title">Daftar Laporan Sedang Dalam Perbaikan</h3>
      </div>
      
      <div class="card-body table-responsive">
        <table id="tabelPengaduanProses" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th width="5%">No</th>
              <th width="15%">Tanggal</th>
              <th width="20%">Pelapor</th>
              <th width="25%">Sarana & Lokasi</th>
              <th>Status</th>
              <th width="15%" class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody id="show_data">
            </tbody>
        </table>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="modalSelesai" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title font-weight-bold">Selesaikan Laporan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="formSelesai">
          <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <p class="font-weight-bold mb-1">Detail Kerusakan:</p>
                    <table class="table table-sm table-borderless bg-light border rounded">
                        <tr><th width="30%">Sarana</th><td>: <span id="dt_sarana"></span></td></tr>
                        <tr><th>Lokasi</th><td>: <span id="dt_lokasi"></span></td></tr>
                        <tr><th>Keluhan</th><td>: <span id="dt_deskripsi"></span></td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <input type="hidden" name="id_pengaduan" id="id_pengaduan">
                    <div class="form-group">
                        <label>Tanggapan / Catatan Perbaikan <span class="text-danger">*</span></label>
                        <textarea name="tanggapan" id="tanggapan" class="form-control" rows="4" placeholder="Jelaskan tindakan apa yang sudah dilakukan untuk memperbaiki kerusakan ini..." required></textarea>
                    </div>
                </div>
            </div>
          </div>
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-success" id="btnSimpanSelesai"><i class="fas fa-check-double"></i> Simpan & Selesaikan</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var interval = setInterval(function() {
        if (window.jQuery) {
            clearInterval(interval);
            jalankanScriptProses();
        }
    }, 100);
});

function jalankanScriptProses() {
    var table = $('#tabelPengaduanProses').DataTable({
        "responsive": true, "autoWidth": false,
    });

    // 1. TAMPIL DATA TABEL STATUS 'PROSES'
    function tampil_data() {
        $.ajax({
            url: '<?= base_url("pengaduan/get_data_proses") ?>',
            type: 'GET',
            dataType: 'JSON',
            success: function(data) {
                table.clear();
                $.each(data, function(i, item) {
                    
                    var badge = '<span class="badge badge-warning"><i class="fas fa-tools"></i> Sedang Diperbaiki</span>';
                    var btnAction = '<button class="btn btn-success btn-sm item_selesai" data-id="'+item.id_pengaduan+'"><i class="fas fa-check"></i> Selesaikan</button>';

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

    // 2. MUNCULKAN MODAL SELESAI
    $('#tabelPengaduanProses').on('click', '.item_selesai', function() {
        var id = $(this).attr('data-id');
        
        // Ambil detail laporan dulu untuk ditampilkan di Modal
        $.ajax({
            url: '<?= base_url("pengaduan/get_detail/") ?>' + id,
            type: 'GET',
            dataType: 'JSON',
            success: function(data) {
                $('#id_pengaduan').val(data.id_pengaduan);
                $('#dt_sarana').text(data.nama_sarana);
                $('#dt_lokasi').text(data.lokasi);
                $('#dt_deskripsi').text(data.deskripsi_laporan);
                $('#tanggapan').val(''); // Kosongkan isi textarea
                
                $('#modalSelesai').modal('show');
            }
        });
    });

    // 3. PROSES SIMPAN TANGGAPAN DAN UBAH STATUS
    $('#formSelesai').on('submit', function(e) {
        e.preventDefault();
        
        var tombol = $('#btnSimpanSelesai');
        tombol.html('<i class="fas fa-spinner fa-spin"></i> Memproses...').attr('disabled', true);

        $.ajax({
            url: '<?= base_url("pengaduan/selesai_laporan") ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'JSON',
            success: function(response) {
                tombol.html('<i class="fas fa-check-double"></i> Simpan & Selesaikan').attr('disabled', false);
                
                if(response.status == 'success') {
                    $('#modalSelesai').modal('hide');
                    tampil_data(); // Refresh tabel
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.pesan,
                        timer: 2500,
                        showConfirmButton: false
                    });
                }
            }
        });
    });
}
</script>
