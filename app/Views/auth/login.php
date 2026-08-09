<!DOCTYPE html>
    <html lang="id">
        <head>
        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?= $title ?></title>

        <link rel="stylesheet"  href="<?= base_url('assets/adminlte/dist/css/adminlte.css') ?>">

        <link rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
        <link rel="stylesheet" href="<?= base_url('assets/css/login.css') ?>">

        <link rel="preconnect"  href="https://fonts.googleapis.com">

        <link rel="preconnect"  href="https://fonts.gstatic.com"  crossorigin>

        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"   rel="stylesheet">

    </head>

<body>

<div class="login-wrapper">

    <div class="login-card">

    <!-- SIDEBAR -->

        <aside class="login-sidebar">

            <div>

                <h2 class="logo-title">
                    <a href="<?= site_url("/")?>" class="text-decoration-none text-white">
                        Absensi Pintar NFC
                    </a>
                </h2>

                <p class="logo-subtitle">

                    Sistem Absensi Sekolah
                    <br>
                    Berbasis NFC

                </p>

            </div>

            <div class="feature-list">

                <div class="feature-item">

                    <i class="fa-solid fa-circle-check"></i>

                    Login Aman

                </div>

                <div class="feature-item">

                    <i class="fa-solid fa-circle-check"></i>

                    Multi Sekolah

                </div>

                <div class="feature-item">

                    <i class="fa-solid fa-circle-check"></i>

                    Berbasis Cloud

                </div>

                <div class="feature-item">

                    <i class="fa-solid fa-circle-check"></i>

                    NFC Terintegrasi

                </div>

            </div>

            <div class="sidebar-image">

                <i class="fa-solid fa-school fa-6x"></i>

            </div>

        </aside>

        <!-- CONTENT -->

        <div class="login-content">

            <div class="page-title mb-5">
                <h2>
                    Selamat Datang 👋
                </h2>
                <p>
                Masuk ke akun Anda untuk melanjutkan ke Dashboard.
                </p>
            </div>
            <div class="login-form">
                <form id="formLogin">
                    <div class="mb-3">
                        <div class="input-group mb-3">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>

                            <input
                                id="login"
                                name="login"
                                type="text"
                                class="form-control"
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
                            autocomplete="current-password"
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
                        type="submit"
                        id="btnLogin"
                        class="btn btn-primary w-100">

                        <i class="fa-solid fa-right-to-bracket me-2"></i>

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
                            <br>
                            <br>

                            <a
                                href="<?= site_url('/') ?>"
                                class="text-decoration-none">
                                <i class="fas fa-arrow-left"></i>
                                Kembali ke Beranda

                            </a>

                    </div>
                    <div class="login-footer text-center mt-5">

                        <small class="text-muted">

                            © <?= date('Y') ?> Absensi Pintar NFC

                        </small>

                    </div>
                </div>

            </div>
            
                
         </div>

        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const BASE_URL = "<?= base_url() ?>/";
</script>

<script src="<?= base_url('assets/js/login.js') ?>"></script>


</body>

</html>