<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>

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
                <h1 class="h2 text-primary mt-2 mb-2 font-weight-bold">Silakan <?= strtoupper($title); ?></h1>
            </div>
            <div class="card-body">
                <!-- <p class="login-box-msg">Silakan Login untuk masuk Aplikasi</p> -->
                <?= $this->session->flashdata('info'); ?>
                <?= $this->session->unset_userdata('info'); ?>
                <form class="user" method="post" action="<?= base_url('auth/registrasi') ?>">
                    <div class="form-group">
                        <input type="text" class="form-control" name="nama_pengguna" id="nama_pengguna" placeholder="Masukan Nama pengguna" value="<?= set_value('nama_pengguna'); ?>">
                        <?= form_error('nama_pengguna', '<span class="text-danger small pl-2">', '</span>'); ?>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap" placeholder="Masukan Nama Lengkap" value="<?= set_value('nama_lengkap'); ?>">
                        <?= form_error('nama_lengkap', '<span class="text-danger small pl-2">', '</span>'); ?>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="email" id="email" placeholder="Masukan Alamat email" value="<?= set_value('email'); ?>">
                        <?= form_error('email', '<span class="text-danger small pl-2">', '</span>'); ?>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                        <?= form_error('password', '<span class="text-danger small pl-2">', '</span>'); ?>
                    </div>
                    <!-- <div class="form-group">
                        <select name="level" id="level" class="form-control">
                            <option value="Admin">Admin</option>
                            <option value="Pengguna" selected>Pengguna</option>
                        </select>
                    </div> -->
                    <button type="submit" class="btn btn-primary btn-user btn-block">
                        Simpan
                    </button>
                </form>
                <hr>
                <div class="text-center small">
                    Sudah punya akun!, <a href="<?= base_url('auth') ?>" style="text-decoration:none;">Silakan Login</a>
                </div>
            </div>
        </div>
    </div>

    <script src="href=" <?= base_url('assets') ?>/plugins/jquery/jquery.min.js"></script>

    <script src="href=" <?= base_url('assets') ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="href=" <?= base_url('assets') ?>/dist/js/adminlte.min.js?v=3.2.0"></script>
</body>

</html>