<!DOCTYPE html>
    <html lang="id">
        <head>
        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?= $title ?></title>

        <link rel="stylesheet"
        href="<?= base_url('assets/adminlte/dist/css/adminlte.css') ?>">

        <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

        <style>
            html,
            body {
                min-height: 100%;
            }

            body.login-page {
                min-height: 100dvh;
                margin: 0;

                display: flex;
                align-items: center;
                justify-content: center;

                padding: 24px 16px;
            }

            .login-box {
                width: 100%;
                max-width: 430px;
                margin: 0;
            }

            .login-box .card {
                width: 100%;
                border-radius: 18px;
                overflow: hidden;

                box-shadow:
                    0 8px 24px rgba(0, 0, 0, 0.08);
            }

            .login-box .card-header {
                padding-top: 32px !important;
                padding-left: 24px;
                padding-right: 24px;
            }

            .login-box .card-header h2 {
                font-size: 1.8rem;
            }

            .login-box .card-header p {
                font-size: 1rem;
            }

            .login-box .card-body {
                padding: 28px 24px 24px;
            }

            .input-group-text {
                min-width: 48px;
                justify-content: center;
            }

            .form-control {
                border-radius: 8px;
            }

            .btn {
                border-radius: 10px;
            }

            /* Input group harus tetap menyatu */
            .input-group > .input-group-text {
                border-radius: 8px 0 0 8px;
            }

            .input-group > .form-control {
                border-radius: 0;
            }

            .input-group > .form-control:last-child {
                border-radius: 0 8px 8px 0;
            }

            #togglePassword {
                border-radius: 0 8px 8px 0;
            }

            .btn-primary {
                min-height: 48px;
                font-weight: 500;
            }

            /* Footer */
            .login-footer {
                padding: 0 24px 24px;
            }

            /*
            * HP dengan tinggi layar kecil.
            * Jangan paksa posisi center agar form tidak terpotong.
            */
            @media (max-height: 650px) {
                body.login-page {
                    align-items: flex-start;
                }
            }

            /* HP kecil */
            @media (max-width: 576px) {
                body.login-page {
                    padding: 20px 16px;
                }

                .login-box {
                    max-width: 100%;
                }

                .login-box .card-header h2 {
                    font-size: 1.6rem;
                }

                .login-box .card-header p {
                    font-size: 0.95rem;
                }

                .login-box .card-body {
                    padding: 24px 20px 20px;
                }
            }
        </style>
        </head>

<body class="login-page bg-body-secondary">

<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center border-0 pt-4">

            <i class="fas fa-wifi fa-3x text-primary mb-3"></i>

            <h2 class="fw-bold mb-1">

                Smart Absensi NFC

            </h2>

            <p class="text-muted mb-0">

                Sistem Absensi Sekolah Berbasis NFC

            </p>

        </div>
    <div class="card-body">
    <form id="formLogin">
        <div class="mb-3">
            <div class="input-group mb-3">

                <span class="input-group-text">

                    <i class="fas fa-user"></i>

                </span>

                <input
                    type="text"
                    class="form-control"
                    name="login"
                    placeholder="Username atau Email"
                    autocomplete="off"
                    required>

            </div>
        </div>

    <div class="mb-3">
        <div class="input-group mb-3">

            <span class="input-group-text">

                <i class="fas fa-lock"></i>

            </span>

            <input
                type="password"
                id="password"
                name="password"
                class="form-control"
                placeholder="Password"
                required>

            <button
                class="btn btn-outline-secondary"
                type="button"
                id="togglePassword">

                <i class="fas fa-eye"></i>

            </button>

        </div>
    </div>
    <div class="form-check mb-3">

        <input
            class="form-check-input"
            type="checkbox"
            id="remember">

        <label
            class="form-check-label"
            for="remember">

            Ingat Saya

        </label>

    </div>
            <button
                class="btn btn-primary w-100 py-2">

                <i class="fas fa-right-to-bracket me-2"></i>

                Masuk

            </button>

        </form>
        
        <div class="text-center mt-4">

                <small>

                    Belum punya akun?

                </small>

                <br>

                <a
                    href="<?= site_url('register') ?>"
                    class="text-decoration-none fw-bold">

                    Daftar Sekarang

                </a>

            </div>
        </div>
           <div class="text-center login-footer">

                <small class="text-muted">

                    © <?= date('Y') ?> Smart Absensi NFC

                </small>

            </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$('#formLogin').submit(function(e){
e.preventDefault();
    $.ajax({
        url:"<?= site_url('login')?>",
        method:"POST",
        data:$(this).serialize(),
        dataType:"json",

success:function(res){

        if(res.status){
                Swal.fire({
                icon:'success',
                title:'Berhasil',
                text:res.message,
                timer:1200,
                showConfirmButton:false
            });

    setTimeout(function(){

            window.location="<?= site_url('dashboard')?>";

        },1200);

            }else{

                Swal.fire({
                    icon:'error',
                    title:'Login Gagal',
                    text:res.message
                });

            }

        }

    });

});

$("#togglePassword").click(function () {

    let input = $("#password");

    let icon = $(this).find("i");

    if (input.attr("type") === "password") {

        input.attr("type", "text");

        icon.removeClass("fa-eye")
            .addClass("fa-eye-slash");

    } else {

        input.attr("type", "password");

        icon.removeClass("fa-eye-slash")
            .addClass("fa-eye");

    }

});
</script>

</body>

</html>