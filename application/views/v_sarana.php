<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Data Master Sarana</h1>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title">Daftar Sarana Prasarana Sekolah</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-primary btn-sm" onclick="bukaModalTambah()">
            <i class="fas fa-plus"></i> Tambah Data
          </button>
        </div>
      </div>
      
      <div class="card-body table-responsive">
        <table id="tabelSarana" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th width="5%">No</th>
              <th>Nama Sarana / Barang</th>
              <th>Lokasi / Ruangan</th>
              <th>Kondisi Saat Ini</th>
              <th width="15%" class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            </tbody>
        </table>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="modalSarana" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalTitle">Tambah Sarana Baru</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="formSarana">
        <div class="modal-body">
          <input type="hidden" name="id_sarana" id="id_sarana">
          
          <div class="form-group">
            <label>Nama Sarana / Barang <span class="text-danger">*</span></label>
            <input type="text" name="nama_sarana" id="nama_sarana" class="form-control" placeholder="Contoh: AC, Komputer, Meja..." required>
          </div>
          
          <div class="form-group">
            <label>Lokasi Ruangan <span class="text-danger">*</span></label>
            <input type="text" name="lokasi" id="lokasi" class="form-control" placeholder="Contoh: Lab Komputer 1" required>
          </div>
          
          <div class="form-group">
            <label>Kondisi Saat Ini <span class="text-danger">*</span></label>
            <select name="kondisi" id="kondisi" class="form-control" required>
              <option value="">- Pilih Kondisi -</option>
              <option value="baik">Baik</option>
              <option value="rusak_ringan">Rusak Ringan</option>
              <option value="rusak_berat">Rusak Berat</option>
            </select>
          </div>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary" id="btnSimpan"><i class="fas fa-save"></i> Simpan Data</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// Fungsi ini memastikan Script hanya berjalan SETELAH jQuery dari footer.php selesai dimuat
document.addEventListener("DOMContentLoaded", function() {
    var interval = setInterval(function() {
        if (window.jQuery) {
            clearInterval(interval);
            jalankanScriptSarana(); // Panggil fungsi utama jika jQuery sudah siap
        }
    }, 100);
});

// Fungsi ini digunakan agar tombol Tambah Data bisa memanggil Modal
function bukaModalTambah() {
    $('#formSarana')[0].reset(); // Kosongkan isian form
    $('#id_sarana').val('');     // Kosongkan ID
    $('#modalTitle').text('Tambah Sarana Baru'); // Ubah judul modal
    $('#modalSarana').modal('show'); // Tampilkan modal
}

// Fungsi Utama AJAX dan DataTables
function jalankanScriptSarana() {
    // 1. Inisialisasi DataTables
    var table = $('#tabelSarana').DataTable({
        "responsive": true,
        "autoWidth": false,
    });

    // 2. Fungsi untuk mengambil data (Read)
    function tampil_data() {
        $.ajax({
            url: '<?= base_url("sarana/get_data") ?>',
            type: 'GET',
            dataType: 'JSON',
            success: function(data) {
                table.clear(); // Bersihkan isi tabel lama
                $.each(data, function(i, item) {
                    // Buat Badge Kondisi
                    var badge = '';
                    if(item.kondisi_saat_ini == 'baik') {
                        badge = '<span class="badge badge-success">Baik</span>';
                    } else if(item.kondisi_saat_ini == 'rusak_ringan') {
                        badge = '<span class="badge badge-warning">Rusak Ringan</span>';
                    } else {
                        badge = '<span class="badge badge-danger">Rusak Berat</span>';
                    }

                    // Buat Tombol Aksi
                    var btnAction = '<button class="btn btn-warning btn-sm item_edit mx-1" data-id="'+item.id_sarana+'"><i class="fas fa-edit"></i></button> ' +
                                    '<button class="btn btn-danger btn-sm item_hapus mx-1" data-id="'+item.id_sarana+'"><i class="fas fa-trash"></i></button>';

                    // Masukkan data ke baris DataTables
                    table.row.add([
                        (i + 1),
                        item.nama_sarana,
                        item.lokasi,
                        badge,
                        btnAction
                    ]).node().id = 'row-' + item.id_sarana;
                });
                table.draw(); // Render ulang tabel
            }
        });
    }

    // Panggil fungsi tampil_data saat halaman pertama kali dibuka
    tampil_data();

    // 3. Proses Simpan / Edit (Create & Update)
    $('#formSarana').on('submit', function(e) {
        e.preventDefault(); // Mencegah halaman reload
        
        var tombol = $('#btnSimpan');
        tombol.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').attr('disabled', true);

        $.ajax({
            url: '<?= base_url("sarana/simpan") ?>',
            type: 'POST',
            data: $(this).serialize(), // Ambil isi form
            dataType: 'JSON',
            success: function(response) {
                tombol.html('<i class="fas fa-save"></i> Simpan Data').attr('disabled', false);
                
                if(response.status == 'success') {
                    $('#modalSarana').modal('hide'); // Tutup modal
                    tampil_data(); // Refresh tabel otomatis
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.pesan,
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            },
            error: function() {
                tombol.html('<i class="fas fa-save"></i> Simpan Data').attr('disabled', false);
                Swal.fire('Error!', 'Terjadi kesalahan pada server.', 'error');
            }
        });
    });

    // 4. Proses Menampilkan Data ke Form Edit (Read by ID)
    $('#tabelSarana').on('click', '.item_edit', function() {
        var id = $(this).attr('data-id');
        
        $.ajax({
            url: '<?= base_url("sarana/get_by_id/") ?>' + id,
            type: 'GET',
            dataType: 'JSON',
            success: function(data) {
                $('#id_sarana').val(data.id_sarana);
                $('#nama_sarana').val(data.nama_sarana);
                $('#lokasi').val(data.lokasi);
                $('#kondisi').val(data.kondisi_saat_ini);
                
                $('#modalTitle').text('Edit Data Sarana'); // Ubah Judul Modal
                $('#modalSarana').modal('show'); // Munculkan Modal
            }
        });
    });

    // 5. Proses Hapus Data (Delete)
    $('#tabelSarana').on('click', '.item_hapus', function() {
        var id = $(this).attr('data-id');
        
        Swal.fire({
            title: 'Hapus Data?',
            text: "Data sarana yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("sarana/hapus/") ?>' + id,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(response) {
                        if(response.status == 'success') {
                            tampil_data(); // Refresh tabel
                            Swal.fire('Terhapus!', response.pesan, 'success');
                        }
                    }
                });
            }
        });
    });
}
</script>
