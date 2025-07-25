<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Aplikasi Kinerja & Asset PDAM Bondowoso" />
    <meta name="author" content="DIE Art'S Production" />
    <title>Kinerja | <?= $title ?></title>

    <link href="<?= base_url() ?>assets/img/pdam_biru.png" rel="icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href=" <?= base_url('assets') ?>/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href=" <?= base_url('assets') ?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href=" <?= base_url('assets') ?>/dist/css/adminlte.min.css?v=3.2.0">
</head>

<body class="hold-transition login-page">

    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <img src="<?= base_url('assets/img/pdam_biru.png') ?>" alt="" style="width:25% ;">
                <h3 class=" text-primary mt-2 mb-2 font-weight-bold">Aplikasi Kinerja & Asset </h3>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Silakan Login untuk masuk Aplikasi</p>
                <?= $this->session->flashdata('info'); ?>
                <?= $this->session->unset_userdata('info'); ?>
                <form class="user" method="post" action="<?= base_url('auth') ?>">
                    <div class="form-group">
                        <input type="text" class="form-control form-control-user" name="nama_pengguna" id="nama_pengguna" placeholder="Masukan nama pengguna" value="<?= set_value('nama_lengkap'); ?>">
                        <?= form_error('nama_pengguna', '<span class="text-danger small pl-2">', '</span>'); ?>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control form-control-user" name="password" id="password" placeholder="Password">
                        <?= form_error('password', '<span class="text-danger small pl-2">', '</span>'); ?>
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block">
                        Login
                    </button>
                </form>
                <!-- <hr>
                <div class="text-center small">
                    Belum punya akun!, <a href="<?= base_url('auth/registrasi') ?>" style="text-decoration:none;">Silakan Register</a>
                </div> -->
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets') ?>/plugins/jquery/jquery.min.js"></script>
    <script src="<?= base_url('assets') ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets') ?>/dist/js/adminlte.min.js?v=3.2.0"></script>
    <script src="<?= base_url('assets') ?>/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>

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
</body>

</html>