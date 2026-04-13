<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Laporan Masuk</h1>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <div class="card card-danger card-outline">
      <div class="card-header">
        <h3 class="card-title">Daftar Laporan Baru (Belum Diproses)</h3>
      </div>
      
      <div class="card-body table-responsive">
        <table id="tabelPengaduan" class="table table-bordered table-striped">
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

<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Detail Laporan Kerusakan</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-sm table-borderless">
                    <tr>
                        <th width="40%">Pelapor</th>
                        <td>: <span id="dt_pelapor"></span></td>
                    </tr>
                    <tr>
                        <th>Tanggal Lapor</th>
                        <td>: <span id="dt_tanggal"></span></td>
                    </tr>
                    <tr>
                        <th>Sarana</th>
                        <td>: <span id="dt_sarana" class="font-weight-bold"></span></td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td>: <span id="dt_lokasi"></span></td>
                    </tr>
                </table>
                <hr>
                <p class="font-weight-bold mb-1">Deskripsi Kerusakan:</p>
                <div class="p-2 bg-light border rounded" id="dt_deskripsi" style="min-height: 80px;"></div>
            </div>
            <div class="col-md-6 text-center">
                <p class="font-weight-bold mb-1">Foto Bukti:</p>
                <img id="dt_foto" src="" class="img-fluid img-thumbnail" style="max-height: 250px; width: auto;" alt="Foto Bukti">
            </div>
        </div>
      </div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-success" id="btnProsesLaporan"><i class="fas fa-tools"></i> Proses Laporan Ini</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var interval = setInterval(function() {
        if (window.jQuery) {
            clearInterval(interval);
            jalankanScriptPengaduan();
        }
    }, 100);
});

function jalankanScriptPengaduan() {
    var table = $('#tabelPengaduan').DataTable({
        "responsive": true, "autoWidth": false,
    });

    var selectedId = ''; // Menyimpan ID yang sedang dilihat

    // 1. TAMPIL DATA TABEL
    function tampil_data() {
        $.ajax({
            url: '<?= base_url("pengaduan/get_data_masuk") ?>',
            type: 'GET',
            dataType: 'JSON',
            success: function(data) {
                table.clear();
                $.each(data, function(i, item) {
                    
                    var badge = '<span class="badge badge-danger"><i class="fas fa-exclamation-circle"></i> Menunggu</span>';
                    var btnAction = '<button class="btn btn-info btn-sm item_detail" data-id="'+item.id_pengaduan+'"><i class="fas fa-search"></i> Detail</button>';

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

    // 2. LIHAT DETAIL LAPORAN
    $('#tabelPengaduan').on('click', '.item_detail', function() {
        var id = $(this).attr('data-id');
        selectedId = id; // Simpan ID untuk diproses nanti
        
        $.ajax({
            url: '<?= base_url("pengaduan/get_detail/") ?>' + id,
            type: 'GET',
            dataType: 'JSON',
            success: function(data) {
                $('#dt_pelapor').text(data.nama_pelapor);
                $('#dt_tanggal').text(data.tgl_pengaduan);
                $('#dt_sarana').text(data.nama_sarana);
                $('#dt_lokasi').text(data.lokasi);
                $('#dt_deskripsi').text(data.deskripsi_laporan);
                
                // Cek apakah ada foto
                if(data.foto && data.foto != '') {
                    $('#dt_foto').attr('src', '<?= base_url("assets/img/pengaduan/") ?>' + data.foto);
                } else {
                    $('#dt_foto').attr('src', 'https://via.placeholder.com/300x200?text=Tidak+Ada+Foto');
                }
                
                $('#modalDetail').modal('show');
            }
        });
    });

    // 3. PROSES LAPORAN (Ubah Status ke 'proses')
    $('#btnProsesLaporan').on('click', function() {
        Swal.fire({
            title: 'Proses Laporan?',
            text: "Laporan akan dipindahkan ke menu 'Laporan Diproses'.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Proses Sekarang!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("pengaduan/proses_laporan/") ?>' + selectedId,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(response) {
                        if(response.status == 'success') {
                            $('#modalDetail').modal('hide');
                            tampil_data(); // Refresh tabel
                            Swal.fire('Berhasil!', response.pesan, 'success');
                        }
                    }
                });
            }
        });
    });
}
</script>
