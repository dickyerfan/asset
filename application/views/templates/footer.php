<footer class="main-footer">
    <!-- <div class="float-right d-none d-sm-block">
        <b>Version</b> 3.2.0
    </div> -->
    <!-- <strong>Copyright &copy; 2024 Pdam Bondowoso.</strong> All rights reserved. -->
    <button id="btn-up"><i class="fas fa-chevron-circle-up logo"></i></button>
    <div class="text-muted">Built With <span class="text-danger">&hearts;</span> by DIE Art'S Production <?= date('Y'); ?></div>
</footer>
<aside class="control-sidebar control-sidebar-dark">

</aside>

</div>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white" id="exampleModalLabel">Yakin Mau Logout.?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Pilih "Logout" jika anda yakin mau keluar</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="<?= base_url('auth/logout'); ?>">Logout</a>
            </div>
        </div>
    </div>
</div>


<script src="<?= base_url('assets') ?>/plugins/jquery/jquery.min.js"></script>
<!-- Sweetalert2 -->
<script src="<?php echo base_url('assets/dist/js/'); ?>sweetalert2.all.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets') ?>/dist/js/adminlte.min.js?v=3.2.0"></script>
<script src="<?= base_url('assets') ?>/plugins/chart.js/Chart.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('assets') ?>/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<!-- select2 -->
<script src="<?= base_url() ?>assets/select2/select2.min.js"></script>
<!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->


<script>
    function alert_edit_neraca() {
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan!',
            text: 'Data ini sudah pernah diperbarui dan tidak bisa diedit lagi!..silakan hubungi Administrator',
            confirmButtonText: 'OK'
        });
    }
</script>
<script>
    function alert_edit_lr_sak_ep()() {
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan!',
            text: 'Data ini sudah pernah diperbarui dan tidak bisa diedit lagi!..silakan hubungi Administrator',
            confirmButtonText: 'OK'
        });
    }
</script>


<script>
    function redirectToPage() {
        var select = document.getElementById("jenis_transaksi");
        var url = select.value;
        if (url) {
            window.location.href = url;
        }
    }
</script>
<script>
    $(document).ready(function() {
        $('#status').on('change', function() {
            if ($(this).val() === '2') {
                $('#tanggal-persediaan-group').show(); // Tampilkan elemen
            } else {
                $('#tanggal-persediaan-group').hide(); // Sembunyikan elemen
            }
        });
    });
</script>


<script>
    $(document).ready(function() {
        $('#tanggal').change(function() {
            $('#form_tanggal').submit();
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#tahun').change(function() {
            $('#form_tahun').submit();
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#upk_bagian').change(function() {
            $('#form_upk_bagian').submit();
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#no_per').change(function() {
            $('#form_no_per').submit();
        });
    });
</script>

<script>
    window.setTimeout(function() {
        $(".alert").animate({
            left: "0",
            width: "80%" // Menggunakan persentase lebar agar lebih responsif
        }, 5000, function() {
            // Animasi selesai
        }).fadeTo(1000, 0).slideUp(1000, function() {
            $(this).remove();
        });
    }, 1000);
</script>

<script>
    $("#btn-up").click(function() {
        $("html,body").animate({
            scrollTop: 0
        }, 500);
    });
</script>

<!-- <script>
    $(function() {
        $('.select2').select2({
            selectOnClose: true
        })
    });
</script> -->

<script>
    $(document).ready(function() {
        // Inisialisasi select2 dengan tema bootstrap-5
        $('.select2').select2({
            theme: 'bootstrap-5'
        });

        // Pastikan fokus ke input pencarian select2 saat dropdown terbuka
        $(document).on('select2:open', function() {
            setTimeout(function() {
                var searchField = $('.select2-container--open .select2-search__field');
                if (searchField.length > 0) {
                    searchField[0].focus(); // Fokuskan ke input secara langsung menggunakan [0] untuk akses DOM
                }
            }, 100); // Jeda waktu untuk memastikan elemen sudah muncul
        });
    });
</script>

<script>
    // Fungsi untuk menampilkan SweetAlert dengan pesan khusus
    function showAlert(message) {
        Swal.fire({
            title: 'Peringatan',
            text: message,
            icon: 'error',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    }

    function confirmReset(id_asset, nama_asset, tanggal) {
        Swal.fire({
            title: 'Konfirmasi Pengembalian Nilai Asset',
            html: `Apakah Anda yakin ingin mengembalikan nilai asset <b>"${nama_asset}"</b> dengan tanggal perolehan <b>${tanggal}</b>..?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, ubah nilai!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect ke URL reset asset jika dikonfirmasi
                window.location.href = '<?= base_url(); ?>asset/edit_asset_semua/' + id_asset;
            }
        });
    }
</script>




<script>
    $(function() {
        $("#tabel_matrik").DataTable({
            "responsive": true,
            "lengthChange": true,
            "pageLength": 5,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#tabel_matrik_wrapper .col-md-6:eq(0)');

        $("#tabel_tingkat").DataTable({
            "responsive": true,
            "lengthChange": true,
            "pageLength": 5,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#tabel_tingkat_wrapper .col-md-6:eq(0)');

        $("#tabel_kategori").DataTable({
            "responsive": true,
            "lengthChange": true,
            "pageLength": 5,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#tabel_kategori_wrapper .col-md-6:eq(0)');

        $("#tabel_bagian").DataTable({
            "responsive": true,
            "lengthChange": true,
            "pageLength": 5,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#tabel_bagian_wrapper .col-md-6:eq(0)');

        $("#tabel_pemilik").DataTable({
            "responsive": true,
            "lengthChange": true,
            "pageLength": 5,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#tabel_pemilik_wrapper .col-md-6:eq(0)');

        // Inisialisasi DataTable untuk id 'contoh' agar file lain tetap berfungsi
        $('#contoh').DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#contoh_wrapper .col-md-6:eq(0)');
        $('#contoh2').DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#contoh2_wrapper .col-md-6:eq(0)');
        $('#contoh3').DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#contoh3_wrapper .col-md-6:eq(0)');
        $('#contoh4').DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#contoh4_wrapper .col-md-6:eq(0)');

        $('#example2').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>
<script>
    const ctx = document.getElementById('barChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
            datasets: [{
                label: 'Contoh Chart',
                data: [12, 19, 3, 5, 2, 3, 0],
                backgroundColor: [
                    // 'rgba(255, 99, 132, 0.2)',
                    // 'rgba(54, 162, 235, 0.2)',
                    // 'rgba(255, 206, 86, 0.2)',
                    // 'rgba(75, 192, 192, 0.2)',
                    // 'rgba(153, 102, 255, 0.2)',
                    // 'rgba(255, 159, 64, 0.2)'
                    'red', 'blue', 'yellow', 'green', 'purple', 'orange'
                ],
                borderColor: [
                    'red', 'blue', 'yellow', 'green', 'purple', 'orange'
                    // 'rgba(255, 99, 132, 1)',
                    // 'rgba(54, 162, 235, 1)',
                    // 'rgba(255, 206, 86, 1)',
                    // 'rgba(75, 192, 192, 1)',
                    // 'rgba(153, 102, 255, 1)',
                    // 'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
<script>
    $('.badge-danger').on('click', function(e) {
        e.preventDefault();
        const href = $(this).attr('href');
        Swal.fire({
            title: 'Yakin mau Di Hapus?',
            text: 'Jika yakin tekan Hapus',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                document.location.href = href;
            }
        })
    })
</script>
<script>
    $(document).ready(function() {
        let options = {
            "Koreksi Fiskal Positif": [{
                    value: "Beban Rapat dan Tamu",
                    text: "Beban Rapat dan Tamu"
                },
                {
                    value: "Beban Representasi",
                    text: "Beban Representasi"
                },
                {
                    value: "Beban Penyisihan Piutang",
                    text: "Beban Penyisihan Piutang"
                }
            ],
            "Koreksi Fiskal Negatif": [{
                value: "Pendapatan bunga giro dan Deposito",
                text: "Pendapatan bunga giro dan Deposito"
            }]
        };

        $("#jenis_bpk").change(function() {
            let jenis = $(this).val();
            let $namaBpk = $("#nama_bpk");

            // Kosongkan dropdown nama_bpk
            $namaBpk.empty();
            $namaBpk.append('<option value="">Pilih Uraian</option>');

            // Jika ada opsi yang sesuai dengan jenis yang dipilih
            if (jenis in options) {
                $.each(options[jenis], function(index, item) {
                    $namaBpk.append($('<option>', {
                        value: item.value,
                        text: item.text
                    }));
                });
            }
        });
    });
</script>
<script>
    $('.tombolHapus').on('click', function(e) {
        e.preventDefault();

        const href = $(this).attr('href');
        const user = $(this).data('user');

        if (user !== 'Administrator') {
            Swal.fire({
                icon: 'error',
                title: 'Akses Ditolak',
                text: 'Anda tidak memiliki izin untuk menghapus file ini.'
            });
            return; // keluar dari fungsi
        }

        Swal.fire({
            title: 'Yakin mau Di Hapus?',
            text: 'Jika yakin tekan Hapus',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                document.location.href = href;
            }
        });
    });
</script>

<!-- <script>
    $(function() {
        // Get context with jQuery - using jQuery's .get() method.
        var areaChartCanvas = $('#barChart').get(0).getContext('2d')

        var areaChartData = {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets: [{
                    label: 'Digital Goods',
                    backgroundColor: ['rgba(60,141,188,0.9)', 'red', 'yellow', 'green', 'blue', 'orange', 'maroon'],
                    borderColor: 'rgba(60,141,188,0.8)',
                    pointRadius: false,
                    pointColor: '#3b8bba',
                    pointStrokeColor: 'rgba(60,141,188,1)',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data: [28, 48, 40, 19, 86, 27, 90]
                },
                {
                    label: 'Electronics',
                    backgroundColor: 'rgba(210, 214, 222, 1)',
                    borderColor: 'rgba(210, 214, 222, 1)',
                    pointRadius: false,
                    pointColor: 'rgba(210, 214, 222, 1)',
                    pointStrokeColor: '#c1c7d1',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(220,220,220,1)',
                    data: [65, 59, 80, 81, 56, 55, 40]
                },
            ]
        }

        //-------------
        //- BAR CHART -
        //-------------
        var barChartCanvas = $('#barChart').get(0).getContext('2d')
        var barChartData = $.extend(true, {}, areaChartData)
        var temp0 = areaChartData.datasets[0]
        var temp1 = areaChartData.datasets[1]
        barChartData.datasets[0] = temp1
        barChartData.datasets[1] = temp0

        var barChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            datasetFill: false
        }

        new Chart(barChartCanvas, {
            type: 'bar',
            data: barChartData,
            options: barChartOptions
        })

    })
</script> -->
</body>

</html>