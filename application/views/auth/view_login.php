<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Aplikasi Kinerja, Manajemen Risiko, RPAM & Ruang Arsip Ijen Tirta" />
    <meta name="author" content="DIE Art'S Production" />
    <title>Ijen Tirta | <?= $title ?></title>

    <link href="<?= base_url() ?>assets/img/logo_ijen_tirta.png" rel="icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/fontawesome-free/css/all.min.css">

    <style>
        :root {
            --sky: #38bdf8;
            --text: #eaf6ff;
            --muted: #9fc9e8;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            min-height: 100%;
        }

        body {
            margin: 0;
            min-height: 100vh;
            padding: 32px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            font-family: 'Poppins', 'Source Sans Pro', sans-serif;
            color: var(--text);
            background: radial-gradient(circle at 15% 10%, #0d4a7a 0%, transparent 45%),
                radial-gradient(circle at 88% 90%, #0b3f6b 0%, transparent 50%),
                linear-gradient(150deg, #06233d 0%, #041a2f 55%, #020e1c 100%);
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background-image: radial-gradient(rgba(140, 215, 255, .10) 1px, transparent 1px);
            background-size: 34px 34px;
            mask-image: radial-gradient(circle at center, #000 10%, transparent 80%);
            -webkit-mask-image: radial-gradient(circle at center, #000 10%, transparent 80%);
            pointer-events: none;
        }

        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(70px);
            opacity: .55;
            pointer-events: none;
            animation: float 14s ease-in-out infinite;
        }

        .orb.one {
            width: 420px;
            height: 420px;
            top: -120px;
            left: -120px;
            background: rgba(14, 165, 233, .45);
        }

        .orb.two {
            width: 360px;
            height: 360px;
            bottom: -140px;
            right: -100px;
            background: rgba(34, 211, 238, .30);
            animation-delay: -5s;
        }

        .orb.three {
            width: 260px;
            height: 260px;
            top: 45%;
            left: 60%;
            background: rgba(59, 130, 246, .25);
            animation-delay: -9s;
        }

        .auth-shell {
            position: relative;
            display: grid;
            grid-template-columns: minmax(0, 1.06fr) minmax(0, .94fr);
            width: 100%;
            max-width: 1060px;
            border-radius: 32px;
            overflow: hidden;
            background: linear-gradient(160deg, rgba(52, 132, 205, .22), rgba(8, 35, 66, .42));
            border: 1px solid rgba(140, 215, 255, .30);
            box-shadow: 0 32px 70px -20px rgba(0, 0, 0, .8),
                inset 0 1px 0 rgba(255, 255, 255, .16),
                0 0 60px rgba(56, 189, 248, .14);
            backdrop-filter: blur(20px) saturate(140%);
            -webkit-backdrop-filter: blur(20px) saturate(140%);
            animation: fadeUp .8s cubic-bezier(.2, .8, .2, 1) both;
        }

        .auth-shell::before {
            content: "";
            position: absolute;
            top: -40%;
            left: -20%;
            width: 55%;
            height: 90%;
            background: radial-gradient(circle, rgba(255, 255, 255, .12) 0%, transparent 65%);
            transform: rotate(-18deg);
            pointer-events: none;
        }

        .intro {
            position: relative;
            padding: 46px 44px 42px;
            display: flex;
            flex-direction: column;
            background: linear-gradient(155deg, rgba(30, 136, 229, .16), rgba(8, 35, 66, .10));
        }

        .intro::before {
            content: "";
            position: absolute;
            width: 340px;
            height: 340px;
            top: -130px;
            left: -110px;
            background: radial-gradient(circle, rgba(56, 189, 248, .16), transparent 70%);
            pointer-events: none;
        }

        .intro::after {
            content: "";
            position: absolute;
            top: 36px;
            bottom: 36px;
            right: 0;
            width: 1px;
            background: linear-gradient(180deg, transparent, rgba(125, 211, 252, .35), transparent);
        }

        .brand-row {
            position: relative;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .logo-ring {
            width: 82px;
            height: 82px;
            flex-shrink: 0;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: radial-gradient(circle at 50% 30%, rgba(255, 255, 255, .98), rgba(226, 242, 255, .92));
            border: 2px solid rgba(120, 210, 255, .55);
            box-shadow: 0 0 30px rgba(56, 189, 248, .42),
                inset 0 0 16px rgba(56, 189, 248, .22),
                0 10px 22px -10px rgba(0, 0, 0, .6);
            animation: pulseGlow 4.5s ease-in-out infinite;
        }

        .logo-ring img {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }

        .brand-text h1 {
            margin: 0;
            font-size: 1.32rem;
            font-weight: 700;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: #fff;
            text-shadow: 0 0 22px rgba(125, 211, 252, .5);
        }

        .brand-text p {
            margin: 5px 0 0;
            font-size: 1.7rem;
            font-weight: 400;
            letter-spacing: 1.9px;
            text-transform: uppercase;
            color: var(--muted);
        }

        .tagline {
            position: relative;
            margin: 24px 0 26px;
            max-width: 44ch;
            font-size: .82rem;
            font-weight: 300;
            line-height: 1.7;
            color: #b6d8ef;
        }

        .module-list {
            position: relative;
            display: grid;
            gap: 10px;
        }

        .module-item {
            display: grid;
            grid-template-columns: 42px 1fr;
            align-items: center;
            gap: 14px;
            padding: 12px 14px;
            border-radius: 14px;
            background: rgba(4, 35, 64, .34);
            border: 1px solid rgba(120, 190, 240, .14);
            transition: border-color .25s, background .25s, transform .25s;
        }

        .module-item:hover {
            border-color: rgba(120, 210, 255, .38);
            background: rgba(4, 45, 80, .5);
            transform: translateX(3px);
        }

        .module-icon {
            width: 42px;
            height: 42px;
            display: grid;
            place-items: center;
            border-radius: 12px;
            background: linear-gradient(140deg, rgba(56, 189, 248, .22), rgba(14, 165, 233, .10));
            border: 1px solid rgba(125, 211, 252, .3);
            color: #7dd3fc;
            font-size: .95rem;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .12);
        }

        .module-item h3 {
            margin: 0 0 3px;
            font-size: .82rem;
            font-weight: 600;
            letter-spacing: .2px;
            color: #eaf6ff;
        }

        .module-item p {
            margin: 0;
            font-size: .71rem;
            font-weight: 300;
            line-height: 1.45;
            color: #93bdd8;
        }

        .form-panel {
            position: relative;
            padding: 44px 44px 34px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: rgba(2, 20, 38, .22);
        }

        .form-head {
            margin-bottom: 24px;
        }

        .form-head h2 {
            margin: 0;
            font-size: 1.35rem;
            font-weight: 600;
            letter-spacing: .3px;
            color: #fff;
        }

        .form-head p {
            margin: 7px 0 0;
            font-size: .78rem;
            font-weight: 300;
            color: var(--muted);
        }

        .alert {
            position: relative;
            margin-bottom: 18px;
            padding: 11px 36px 11px 14px;
            border-radius: 12px;
            font-size: .78rem;
            line-height: 1.45;
            border: 1px solid transparent;
            animation: fadeUp .4s ease both;
        }

        .alert-success {
            color: #b7f7cf;
            background: rgba(34, 197, 94, .16);
            border-color: rgba(34, 197, 94, .42);
        }

        .alert-danger {
            color: #ffc0c6;
            background: rgba(244, 63, 94, .16);
            border-color: rgba(244, 63, 94, .42);
        }

        .alert .close {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            background: none;
            border: 0;
            padding: 4px 6px;
            color: inherit;
            font-size: 1.15rem;
            line-height: 1;
            opacity: .75;
            cursor: pointer;
        }

        .alert .close:hover {
            opacity: 1;
        }

        .input-group {
            position: relative;
            margin-bottom: 15px;
        }

        .input-group input {
            width: 100%;
            padding: 14px 48px 14px 48px;
            border-radius: 13px;
            background: rgba(3, 32, 58, .55);
            border: 1px solid rgba(120, 190, 240, .24);
            color: var(--text);
            font-family: inherit;
            font-size: .88rem;
            outline: none;
            transition: border-color .25s, box-shadow .25s, background .25s;
        }

        .input-group input::placeholder {
            color: rgba(190, 220, 245, .55);
            font-weight: 300;
        }

        .input-group input:focus {
            border-color: var(--sky);
            background: rgba(3, 32, 58, .78);
            box-shadow: 0 0 0 3px rgba(56, 189, 248, .16), 0 0 22px rgba(56, 189, 248, .18);
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 18px;
            transform: translateY(-50%);
            color: #6fc4ee;
            font-size: .86rem;
            pointer-events: none;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            display: grid;
            place-items: center;
            width: 32px;
            height: 32px;
            border: 0;
            border-radius: 8px;
            background: transparent;
            color: #7ec7ef;
            cursor: pointer;
            transition: color .2s, background .2s;
        }

        .toggle-password:hover {
            color: #bae6fd;
            background: rgba(125, 211, 252, .12);
        }

        .err {
            display: block;
            margin: 6px 0 0 4px;
            font-size: .72rem;
            color: #ff9aa2;
        }

        .options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin: 4px 2px 24px;
            font-size: .76rem;
            color: #bfe0f5;
        }

        .remember {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            user-select: none;
        }

        .remember input {
            width: 15px;
            height: 15px;
            margin: 0;
            accent-color: #38bdf8;
            cursor: pointer;
        }

        .forgot {
            color: #7fd4ff;
            text-decoration: none;
            transition: color .2s;
        }

        .forgot:hover {
            color: #c7ecff;
            text-decoration: underline;
        }

        .btn-login {
            position: relative;
            width: 100%;
            padding: 14px 18px;
            border: 0;
            border-radius: 13px;
            font-family: inherit;
            font-size: .84rem;
            font-weight: 600;
            letter-spacing: 2.2px;
            text-transform: uppercase;
            color: #fff;
            background: linear-gradient(120deg, #1d7fd8 0%, #0ea5e9 55%, #22d3ee 100%);
            background-size: 160% 160%;
            box-shadow: 0 14px 28px -10px rgba(14, 165, 233, .8), inset 0 1px 0 rgba(255, 255, 255, .3);
            cursor: pointer;
            overflow: hidden;
            transition: transform .25s, box-shadow .25s, background-position .5s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            background-position: 100% 50%;
            box-shadow: 0 18px 34px -10px rgba(34, 211, 238, .85), inset 0 1px 0 rgba(255, 255, 255, .35);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login:disabled {
            opacity: .8;
            cursor: default;
            transform: none;
        }

        .btn-login .spinner {
            display: none;
            margin-right: 8px;
            animation: spin 1s linear infinite;
        }

        .btn-login.is-loading .spinner {
            display: inline-block;
        }

        .footer-note {
            margin: 26px 0 0;
            text-align: center;
            font-size: .9rem;
            letter-spacing: .4px;
            color: rgba(159, 201, 232, .7);
        }

        .toast {
            position: fixed;
            left: 50%;
            bottom: 30px;
            transform: translate(-50%, 20px);
            max-width: calc(100% - 40px);
            padding: 12px 20px;
            border-radius: 12px;
            background: rgba(8, 39, 70, .95);
            border: 1px solid rgba(125, 211, 252, .4);
            color: #dbf2ff;
            font-size: .8rem;
            box-shadow: 0 15px 35px -12px rgba(0, 0, 0, .8);
            opacity: 0;
            pointer-events: none;
            transition: opacity .3s, transform .3s;
            z-index: 10;
        }

        .toast.show {
            opacity: 1;
            transform: translate(-50%, 0);
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(26px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translate3d(0, 0, 0) scale(1);
            }

            50% {
                transform: translate3d(20px, -25px, 0) scale(1.06);
            }
        }

        @keyframes pulseGlow {

            0%,
            100% {
                box-shadow: 0 0 26px rgba(56, 189, 248, .36), inset 0 0 16px rgba(56, 189, 248, .2), 0 10px 22px -10px rgba(0, 0, 0, .6);
            }

            50% {
                box-shadow: 0 0 40px rgba(56, 189, 248, .58), inset 0 0 20px rgba(56, 189, 248, .3), 0 10px 22px -10px rgba(0, 0, 0, .6);
            }
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 860px) {
            .auth-shell {
                grid-template-columns: 1fr;
                border-radius: 26px;
            }

            .intro {
                padding: 32px 26px 28px;
            }

            .intro::after {
                top: auto;
                bottom: 0;
                left: 26px;
                right: 26px;
                width: auto;
                height: 1px;
                background: linear-gradient(90deg, transparent, rgba(125, 211, 252, .35), transparent);
            }

            .brand-row {
                justify-content: center;
                text-align: left;
            }

            .tagline {
                margin: 20px auto 22px;
                text-align: center;
            }

            .module-list {
                grid-template-columns: 1fr 1fr;
                gap: 8px;
            }

            .module-item {
                grid-template-columns: 36px 1fr;
                gap: 10px;
                padding: 11px;
            }

            .module-icon {
                width: 36px;
                height: 36px;
                font-size: .82rem;
                border-radius: 10px;
            }

            .module-item h3 {
                font-size: .74rem;
            }

            .module-item p {
                font-size: .65rem;
                line-height: 1.4;
            }

            .module-item:hover {
                transform: none;
            }

            .form-panel {
                padding: 30px 26px 28px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 20px 12px;
            }

            .intro {
                padding: 28px 20px 24px;
            }

            .logo-ring {
                width: 66px;
                height: 66px;
            }

            .logo-ring img {
                width: 48px;
                height: 48px;
            }

            .brand-text h1 {
                font-size: 1.05rem;
                letter-spacing: 3px;
            }

            .brand-text p {
                font-size: .57rem;
                letter-spacing: 1.4px;
            }

            .tagline {
                font-size: .74rem;
                line-height: 1.6;
            }

            .module-item {
                display: block;
                padding: 10px;
            }

            .module-icon {
                width: 30px;
                height: 30px;
                font-size: .72rem;
                margin-bottom: 7px;
            }

            .module-item h3 {
                font-size: .7rem;
                margin-bottom: 2px;
            }

            .module-item p {
                font-size: .61rem;
            }

            .form-panel {
                padding: 26px 20px 24px;
            }

            .form-head h2 {
                font-size: 1.2rem;
            }

            .options {
                font-size: .72rem;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation-duration: .001ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .001ms !important;
            }
        }
    </style>
</head>

<body>
    <div class="orb one"></div>
    <div class="orb two"></div>
    <div class="orb three"></div>

    <main class="auth-shell">
        <section class="intro">
            <div class="brand-row">
                <div class="logo-ring">
                    <img src="<?= base_url('assets/img/logo_ijen_tirta.png') ?>" alt="Logo Ijen Tirta">
                </div>
                <div class="brand-text">
                    <h1>PERUMDAM</h1>
                    <p>IJEN TIRTA</p>
                </div>
            </div>

            <p class="tagline">Platform terintegrasi untuk mengelola kinerja UPK, Manajemen Risiko, dan arsip perusahaan dalam satu tempat.</p>

            <div class="module-list">
                <div class="module-item">
                    <span class="module-icon"><i class="fas fa-chart-line"></i></span>
                    <div>
                        <h3>Kinerja</h3>
                        <p>Mengukur capaian kinerja Perumdam/UPK secara terukur dan berkelanjutan.</p>
                    </div>
                </div>
                <div class="module-item">
                    <span class="module-icon"><i class="fas fa-shield-alt"></i></span>
                    <div>
                        <h3>Manajemen Risiko (MR)</h3>
                        <p>Identifikasi, analisis, dan mitigasi risiko perusahaan.</p>
                    </div>
                </div>
                <div class="module-item">
                    <span class="module-icon"><i class="fas fa-folder-open"></i></span>
                    <div>
                        <h3>Ruang Arsip</h3>
                        <p>Menyimpan dan mengelola dokumen/Arsip secara terpusat dan aman.</p>
                    </div>
                </div>
                <div class="module-item">
                    <span class="module-icon"><i class="fas fa-dollar-sign"></i></span>
                    <div>
                        <h3>Laporan Keuangan</h3>
                        <p>Menyajikan informasi data keuangan perusahaan.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="form-panel">
            <div class="form-head">
                <h2>Selamat Datang</h2>
                <p>Silakan masuk menggunakan akun Anda.</p>
            </div>

            <?= $this->session->flashdata('info'); ?>
            <?= $this->session->unset_userdata('info'); ?>

            <form method="post" action="<?= base_url('auth') ?>" autocomplete="on">
                <div class="input-group">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" name="nama_pengguna" id="nama_pengguna" placeholder="Nama pengguna" value="<?= set_value('nama_pengguna'); ?>" autocomplete="username" required>
                </div>
                <?= form_error('nama_pengguna', '<span class="err">', '</span>'); ?>

                <div class="input-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" name="password" id="password" placeholder="Password" autocomplete="current-password" required>
                    <button type="button" class="toggle-password" id="togglePassword" aria-label="Tampilkan password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <?= form_error('password', '<span class="err">', '</span>'); ?>

                <div class="options">
                    <label class="remember">
                        <input type="checkbox" name="remember" value="1">
                        <span>Remember me</span>
                    </label>
                    <a href="javascript:void(0)" class="forgot" id="forgotLink">Forgot Password?</a>
                </div>

                <button type="submit" class="btn-login" id="btnLogin">
                    <i class="fas fa-circle-notch spinner"></i>Login
                </button>
            </form>

            <p class="footer-note">Built With <span class="login-heart">&hearts;</span> by DIE Art'S Production 2025</p>
        </section>
    </main>

    <div class="toast" id="toast">Silakan hubungi Administrator IT untuk reset password.</div>

    <script>
        (function() {
            var password = document.getElementById('password');
            var toggle = document.getElementById('togglePassword');

            toggle.addEventListener('click', function() {
                var isPassword = password.type === 'password';
                password.type = isPassword ? 'text' : 'password';
                toggle.innerHTML = '<i class="fas fa-eye' + (isPassword ? '-slash' : '') + '"></i>';
                toggle.setAttribute('aria-label', isPassword ? 'Sembunyikan password' : 'Tampilkan password');
            });

            var toast = document.getElementById('toast');
            var toastTimer;

            document.getElementById('forgotLink').addEventListener('click', function() {
                toast.classList.add('show');
                clearTimeout(toastTimer);
                toastTimer = setTimeout(function() {
                    toast.classList.remove('show');
                }, 3200);
            });

            document.querySelectorAll('.alert .close').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var alert = btn.closest('.alert');
                    if (alert) {
                        alert.remove();
                    }
                });
            });

            var form = document.querySelector('form');
            form.addEventListener('submit', function() {
                var button = document.getElementById('btnLogin');
                button.classList.add('is-loading');
                button.disabled = true;
            });
        })();
    </script>
</body>

</html>