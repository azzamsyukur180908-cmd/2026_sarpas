<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Riwayat Laporan Saya</h1>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <div class="card card-info card-outline shadow-sm">
      <div class="card-body table-responsive">
        <table id="tabelRiwayat" class="table table-bordered table-hover">
          <thead class="bg-light">
            <tr>
              <th width="5%">No</th>
              <th width="15%">Tanggal</th>
              <th width="30%">Sarana / Lokasi</th>
              <th width="25%">Keluhan Saya</th>
              <th>Status</th>
              <th width="10%" class="text-center">Detail</th>
            </tr>
          </thead>
          <tbody id="show_data">
            </tbody>
        </table>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="modalPenyelesaian" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-check-circle"></i> Detail Penyelesaian Laporan</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <p>Laporan Anda mengenai <strong><span id="dt_sarana"></span></strong> telah selesai diperbaiki oleh petugas kami.</p>
         <hr>
         <p class="font-weight-bold mb-1">Catatan Petugas (<span id="dt_petugas" class="text-primary"></span>) :</p>
         <div class="p-3 bg-light border rounded text-success font-weight-medium" id="dt_tanggapan"></div>
         <p class="text-muted text-sm mt-2 text-right">Tgl Selesai: <span id="dt_tgl_selesai"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var interval = setInterval(function() {
        if (window.jQuery) {
            clearInterval(interval);
            jalankanScriptRiwayat();
        }
    }, 100);
});

function jalankanScriptRiwayat() {
    var table = $('#tabelRiwayat').DataTable({
        "responsive": true, "autoWidth": false,
    });

    function tampil_data() {
        $.ajax({
            url: '<?= base_url("pengaduan/get_data_riwayat") ?>',
            type: 'GET',
            dataType: 'JSON',
            success: function(data) {
                table.clear();
                $.each(data, function(i, item) {
                    
                    var badge = '';
                    var btnAction = '-';

                    if(item.status == '0') {
                        badge = '<span class="badge badge-danger">Menunggu Proses</span>';
                    } else if(item.status == 'proses') {
                        badge = '<span class="badge badge-warning">Sedang Diperbaiki</span>';
                    } else {
                        badge = '<span class="badge badge-success">Selesai</span>';
                        // Jika selesai, munculkan tombol lihat tanggapan
                        btnAction = '<button class="btn btn-success btn-sm item_detail" data-id="'+item.id_pengaduan+'"><i class="fas fa-search"></i> Hasil</button>';
                    }

                    table.row.add([
                        (i + 1),
                        item.tgl_pengaduan,
                        '<b>' + item.nama_sarana + '</b><br><small><i class="fas fa-map-marker-alt"></i> ' + item.lokasi + '</small>',
                        item.deskripsi_laporan,
                        badge,
                        '<div class="text-center">' + btnAction + '</div>'
                    ]);
                });
                table.draw();
            }
        });
    }

    tampil_data();

    // LIHAT TANGGAPAN JIKA SUDAH SELESAI
    $('#tabelRiwayat').on('click', '.item_detail', function() {
        var id = $(this).attr('data-id');
        
        // Meminjam API get_detail_selesai yang ada di controller admin
        $.ajax({
            url: '<?= base_url("pengaduan/get_detail_selesai/") ?>' + id,
            type: 'GET',
            dataType: 'JSON',
            success: function(data) {
                $('#dt_sarana').text(data.laporan.nama_sarana);
                $('#dt_petugas').text(data.tanggapan.nama_petugas);
                $('#dt_tanggapan').text(data.tanggapan.tanggapan);
                $('#dt_tgl_selesai').text(data.tanggapan.tgl_tanggapan);
                
                $('#modalPenyelesaian').modal('show');
            }
        });
    });
}
</script>
