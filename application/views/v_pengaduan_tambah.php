<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Buat Laporan Baru</h1>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-8">
        <div class="card card-primary card-outline shadow-sm">
          <div class="card-header">
            <h3 class="card-title">Form Pengaduan Kerusakan Sarana</h3>
          </div>
          
          <form id="formLapor" enctype="multipart/form-data">
            <div class="card-body">
              
              <div class="form-group">
                <label>Pilih Sarana / Barang yang Rusak <span class="text-danger">*</span></label>
                <select name="id_sarana" class="form-control" required>
                  <option value="">- Cari dan Pilih Sarana -</option>
                  <?php foreach($sarana as $s): ?>
                    <option value="<?= $s->id_sarana ?>"><?= $s->nama_sarana ?> (Lokasi: <?= $s->lokasi ?>)</option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="form-group">
                <label>Deskripsi Kerusakan <span class="text-danger">*</span></label>
                <textarea name="deskripsi_laporan" class="form-control" rows="4" placeholder="Jelaskan bagian mana yang rusak atau kendala apa yang terjadi..." required></textarea>
              </div>

              <div class="form-group">
                <label>Upload Foto Bukti <span class="text-danger">*</span></label>
                <input type="file" name="foto" class="form-control-file" accept="image/*" required>
                <small class="text-muted">Format: JPG/PNG. Maksimal ukuran file 2 MB.</small>
              </div>

            </div>
            <div class="card-footer bg-light">
              <button type="submit" class="btn btn-primary" id="btnKirim"><i class="fas fa-paper-plane"></i> Kirim Laporan</button>
              <button type="reset" class="btn btn-secondary float-right">Reset</button>
            </div>
          </form>

        </div>
      </div>
      
      <div class="col-md-4">
          <div class="alert alert-info shadow-sm">
              <h5><i class="icon fas fa-info"></i> Panduan Melapor</h5>
              <ol class="pl-3 mb-0 text-sm">
                  <li class="mb-1">Pastikan Anda memilih <strong>Sarana</strong> yang tepat sesuai dengan lokasinya.</li>
                  <li class="mb-1">Tulis <strong>Deskripsi</strong> dengan jelas agar petugas mudah memahami kerusakannya.</li>
                  <li>Sertakan <strong>Foto Bukti</strong> yang terang dan tidak blur (Maksimal 2MB).</li>
              </ol>
          </div>
      </div>
    </div>
  </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var interval = setInterval(function() {
        if (window.jQuery) {
            clearInterval(interval);
            jalankanScriptLapor();
        }
    }, 100);
});

function jalankanScriptLapor() {
    $('#formLapor').on('submit', function(e) {
        e.preventDefault();
        
        var tombol = $('#btnKirim');
        tombol.html('<i class="fas fa-spinner fa-spin"></i> Mengirim...').attr('disabled', true);

        // Menggunakan FormData karena ada upload file (foto)
        var formData = new FormData(this);

        $.ajax({
            url: '<?= base_url("pengaduan/simpan_pengaduan") ?>',
            type: 'POST',
            data: formData,
            contentType: false, // Wajib false untuk file upload
            processData: false, // Wajib false untuk file upload
            dataType: 'JSON',
            success: function(response) {
                tombol.html('<i class="fas fa-paper-plane"></i> Kirim Laporan').attr('disabled', false);
                
                if(response.status == 'success') {
                    $('#formLapor')[0].reset(); // Kosongkan form
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.pesan,
                    }).then((result) => {
                        // Arahkan ke menu riwayat setelah sukses
                        window.location.href = "<?= base_url('pengaduan/riwayat') ?>";
                    });
                } else {
                    Swal.fire('Gagal!', response.pesan, 'error');
                }
            }
        });
    });
}
</script>
