<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Manajemen User</h1>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title">Daftar Pengguna Sistem</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-primary btn-sm" onclick="bukaModalTambah()">
            <i class="fas fa-plus"></i> Tambah User
          </button>
        </div>
      </div>
      
      <div class="card-body table-responsive">
        <table id="tabelUsers" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th width="5%">No</th>
              <th>Nama Lengkap</th>
              <th>Username</th>
              <th>Level / Role</th>
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

<div class="modal fade" id="modalUser" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalTitle">Tambah User Baru</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="formUser">
        <div class="modal-body">
          <input type="hidden" name="id_user" id="id_user">
          
          <div class="form-group">
            <label>Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
          </div>
          
          <div class="form-group">
            <label>Username <span class="text-danger">*</span></label>
            <input type="text" name="username" id="username" class="form-control" placeholder="Gunakan huruf kecil tanpa spasi" required>
          </div>
          
          <div class="form-group">
            <label>Password <span class="text-danger" id="req_pass">*</span></label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password">
            <small class="text-muted" id="hint_pass" style="display:none;">Kosongkan jika tidak ingin mengubah password.</small>
          </div>
          
          <div class="form-group">
            <label>Level Akses <span class="text-danger">*</span></label>
            <select name="level" id="level" class="form-control" required>
              <option value="">- Pilih Role -</option>
              <option value="admin">Administrator</option>
              <option value="petugas">Petugas Sarpras</option>
              <option value="pelapor">Pelapor (Guru/Siswa)</option>
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
document.addEventListener("DOMContentLoaded", function() {
    var interval = setInterval(function() {
        if (window.jQuery) {
            clearInterval(interval);
            jalankanScriptUser();
        }
    }, 100);
});

function bukaModalTambah() {
    $('#formUser')[0].reset(); 
    $('#id_user').val('');     
    $('#password').prop('required', true); // Password wajib diisi saat tambah baru
    $('#req_pass').show();
    $('#hint_pass').hide();
    
    $('#modalTitle').text('Tambah User Baru'); 
    $('#modalUser').modal('show'); 
}

function jalankanScriptUser() {
    var table = $('#tabelUsers').DataTable({
        "responsive": true, "autoWidth": false,
    });

    function tampil_data() {
        $.ajax({
            url: '<?= base_url("users/get_data") ?>',
            type: 'GET',
            dataType: 'JSON',
            success: function(data) {
                table.clear();
                $.each(data, function(i, item) {
                    // Logika Label/Badge Level
                    var badge = '';
                    if(item.level == 'admin') {
                        badge = '<span class="badge badge-primary">Administrator</span>';
                    } else if(item.level == 'petugas') {
                        badge = '<span class="badge badge-info">Petugas Sarpras</span>';
                    } else {
                        badge = '<span class="badge badge-secondary">Pelapor</span>';
                    }

                    var btnAction = '<button class="btn btn-warning btn-sm item_edit mx-1" data-id="'+item.id_user+'"><i class="fas fa-edit"></i></button> ' +
                                    '<button class="btn btn-danger btn-sm item_hapus mx-1" data-id="'+item.id_user+'"><i class="fas fa-trash"></i></button>';

                    table.row.add([
                        (i + 1),
                        item.nama,
                        item.username,
                        badge,
                        btnAction
                    ]);
                });
                table.draw();
            }
        });
    }

    tampil_data();

    // PROSES SIMPAN / EDIT
    $('#formUser').on('submit', function(e) {
        e.preventDefault();
        var tombol = $('#btnSimpan');
        tombol.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').attr('disabled', true);

        $.ajax({
            url: '<?= base_url("users/simpan") ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'JSON',
            success: function(response) {
                tombol.html('<i class="fas fa-save"></i> Simpan Data').attr('disabled', false);
                
                if(response.status == 'success') {
                    $('#modalUser').modal('hide');
                    tampil_data();
                    Swal.fire({
                        icon: 'success', title: 'Berhasil!', text: response.pesan, timer: 2000, showConfirmButton: false
                    });
                }
            }
        });
    });

    // PROSES AMBIL DATA EDIT
    $('#tabelUsers').on('click', '.item_edit', function() {
        var id = $(this).attr('data-id');
        
        $.ajax({
            url: '<?= base_url("users/get_by_id/") ?>' + id,
            type: 'GET',
            dataType: 'JSON',
            success: function(data) {
                $('#id_user').val(data.id_user);
                $('#nama').val(data.nama);
                $('#username').val(data.username);
                $('#level').val(data.level);
                
                // Karena Edit, password tidak wajib diisi
                $('#password').val('');
                $('#password').prop('required', false);
                $('#req_pass').hide();
                $('#hint_pass').show();
                
                $('#modalTitle').text('Edit Data User');
                $('#modalUser').modal('show');
            }
        });
    });

    // PROSES HAPUS
    $('#tabelUsers').on('click', '.item_hapus', function() {
        var id = $(this).attr('data-id');
        
        Swal.fire({
            title: 'Hapus Akun?',
            text: "User yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("users/hapus/") ?>' + id,
                    type: 'POST',
                    dataType: 'JSON',
                    success: function(response) {
                        if(response.status == 'success') {
                            tampil_data();
                            Swal.fire('Terhapus!', response.pesan, 'success');
                        } else {
                            // Muncul jika admin mencoba menghapus dirinya sendiri
                            Swal.fire('Gagal!', response.pesan, 'error');
                        }
                    }
                });
            }
        });
    });
}
</script>
