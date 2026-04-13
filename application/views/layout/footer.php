<?php
// application/views/layout/footer.php (Versi AdminLTE 3)
defined('BASEPATH') OR exit('No direct script access allowed');
?>
            </div>
            </div>
        <footer class="main-footer">
            <strong>Copyright &copy; 2026 <a href="#">SARPRAS App</a>.</strong>
            
            <div class="float-right d-none d-sm-inline-block">
                <b>Versi</b> 1.0
            </div>
        </footer>

        <aside class="control-sidebar control-sidebar-dark">
            </aside>
        </div>
    <script src="<?php echo base_url('adminlte/plugins/jquery/jquery.min.js'); ?>"></script>
    
    <script src="<?php echo base_url('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    
    <script src="<?php echo base_url('adminlte/plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="<?php echo base_url('adminlte/dist/js/adminlte.js'); ?>"></script> 

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="<?php echo base_url('adminlte/plugins/moment/moment.min.js'); ?>"></script>
    <script src="<?php echo base_url('adminlte/plugins/daterangepicker/daterangepicker.js'); ?>"></script>
    <script src="<?php echo base_url('adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js'); ?>"></script>

    <script src="<?php echo base_url('adminlte/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js'); ?>"></script>
    <script src="<?php echo base_url('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js'); ?>"></script>
    <script src="<?php echo base_url('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js'); ?>"></script>
    <script src="<?php echo base_url('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js'); ?>"></script>
    <script src="<?php echo base_url('adminlte/plugins/jszip/jszip.min.js'); ?>"></script>
    <script src="<?php echo base_url('adminlte/plugins/pdfmake/pdfmake.min.js'); ?>"></script>
    <script src="<?php echo base_url('adminlte/plugins/pdfmake/vfs_fonts.js'); ?>"></script>
    <script src="<?php echo base_url('adminlte/plugins/datatables-buttons/js/buttons.html5.min.js'); ?>"></script>
    <script src="<?php echo base_url('adminlte/plugins/datatables-buttons/js/buttons.print.min.js'); ?>"></script>
    <script src="<?php echo base_url('adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js'); ?>"></script>

    <script>
    $(document).ready(function() {
        // --- 1. Logic untuk mengaktifkan menu sidebar (AdminLTE 3) ---
        var currentUrl = window.location.href.split('#')[0].replace('index.php/', '');
        
        $('.nav-link').each(function() {
            var linkUrl = this.href.split('#')[0].replace('index.php/', '');
            if (linkUrl == currentUrl) {
                $(this).addClass('active');
                $(this).parents('.nav-treeview').addClass('menu-open').prev().addClass('active'); 
                $(this).parents('.has-treeview').addClass('menu-open');
            }
        });

        // --- 2. Inisialisasi DataTables (Otomatis untuk tabel class example1 & example2) ---
        if ($.fn.DataTable) {
            if ($("#example1").length > 0) {
                $("#example1").DataTable({
                    "responsive": true, "lengthChange": true, "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            }
            
            if ($("#example2").length > 0) {
                $('#example2').DataTable({
                    "paging": true,
                    "lengthChange": false,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                });
            }
        }
        
        // --- 3. Script Dummy untuk Grafik Pengaduan (Jika nanti ditambahkan div #chartPengaduan) ---
        if ($('#chartPengaduan').length > 0) {
            var ctx = document.getElementById('chartPengaduan').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                    datasets: [{
                        label: 'Laporan Masuk',
                        backgroundColor: '#007bff',
                        data: [12, 19, 3, 5, 2, 3] // Nanti diganti dengan variabel PHP
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }
    });
    </script>
</body>
</html>
