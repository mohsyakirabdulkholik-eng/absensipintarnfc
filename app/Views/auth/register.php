<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title><?= $title ?></title>

    <link rel="stylesheet"
          href="<?= base_url('assets/adminlte/dist/css/adminlte.css') ?>">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet"
      href="<?= base_url('assets/css/register.css') ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

</head>

<body>

<div class="register-wrapper">

    <div class="register-card">

        <!-- SIDEBAR -->
        <aside class="register-sidebar">

            <div>

                <h2 class="logo-title">
                    Absensi Pintar NFC
                </h2>

                <p class="logo-subtitle">
                    Sistem Absensi Sekolah
                    <br>
                    Berbasis NFC
                </p>

            </div>

            <div class="step-list mt-5">

                <div class="step-item active" id="sidebarStep1">

                    <div class="step-number">
                        1
                    </div>

                    <div>

                        <div class="step-title">
                            Data Administrator
                        </div>

                        <small>
                            Informasi akun utama
                        </small>

                    </div>

                </div>

                <div class="step-item" id="sidebarStep2">

                    <div class="step-number">
                        2
                    </div>

                    <div>

                        <div class="step-title">
                            Data Sekolah
                        </div>

                        <small>
                            Profil sekolah
                        </small>

                    </div>

                </div>

            </div>

            <div class="sidebar-image">

                <i class="fa-solid fa-school fa-6x"></i>

            </div>

        </aside>

        <!-- CONTENT -->
        <div class="register-content">

            <form id="formRegister">

                <?= csrf_field() ?>
                <!-- STEP 1 -->
                    <div id="step1" class="step-content">

                        <div class="page-title mb-4">

                            <h2 class="fw-bold">
                                Selamat Datang 👋
                            </h2>

                            <p class="text-muted">
                                Lengkapi data administrator terlebih dahulu.
                            </p>

                        </div>

                        <div class="step-card">

                            <div class="form-floating mb-3">

                                <input
                                    type="text"
                                    class="form-control"
                                    id="nama"
                                    name="nama"
                                    placeholder="Nama Administrator"
                                    required>

                                <label for="nama">

                                    <i class="fa-solid fa-user me-2"></i>

                                    Nama Administrator

                                </label>

                            </div>

                            <div class="form-floating mb-3">

                                <input
                                    type="text"
                                    id="username"
                                    name="username"
                                    class="form-control"
                                    placeholder="Username"
                                    required>

                                <label for="username">

                                    <i class="fa-solid fa-at me-2"></i>

                                    Username

                                </label>

                            </div>

                               <div class="form-floating mb-3">

                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        class="form-control"
                                        placeholder="Email"
                                        required>

                                    <label for="email">

                                        <i class="fa-solid fa-envelope me-2"></i>

                                        Email

                                    </label>

                                </div>


                            <div class="mb-3">

                                <label class="form-label">
                                    Nomor HP
                                </label>

                                <input
                                    type="text"
                                    name="telepon"
                                    class="form-control">

                            </div>

                            <div class="mb-3">

                                <label class="form-label">

                                    Password

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">

                                        <i class="fa-solid fa-lock"></i>

                                    </span>

                                    <input
                                        type="password"
                                        class="form-control"
                                        id="password"
                                        name="password">

                                    <button
                                        class="btn btn-outline-secondary"
                                        type="button"
                                        id="togglePassword">

                                        <i class="fa-solid fa-eye"></i>

                                    </button>

                                </div>

                            </div>

                            <div class="mb-4">

                                <label class="form-label">
                                    Konfirmasi Password
                                </label>

                                <div class="input-group">

                                    <input
                                        type="password"
                                        id="konfirmasiPassword"
                                        name="konfirmasi_password"
                                        class="form-control"
                                        minlength="8"
                                        required>

                                    <button
                                        type="button"
                                        id="toggleKonfirmasi"
                                        class="btn btn-outline-secondary">

                                        <i class="fa-solid fa-eye"></i>

                                    </button>

                                </div>

                            </div>

                            <div class="d-grid">

                                <button
                                    type="button"
                                    id="btnNext"
                                    class="btn btn-primary btn-lg">

                                    Lanjut
                                    <i class="fas fa-arrow-right ms-2"></i>

                                </button>

                            </div>

                        </div>

                    </div>

                    <!-- STEP 2 -->
                    <div id="step2" class="step-content d-none">

                        <div class="page-title mb-4">

                            <h2 class="fw-bold">
                                Data Sekolah
                            </h2>

                            <p class="text-muted">
                                Lengkapi informasi sekolah Anda.
                            </p>

                        </div>

                        <div class="step-card">
                            <div class="mb-3">

                                <label class="form-label">
                                    Nama Sekolah
                                </label>

                                <input
                                    type="text"
                                    name="nama_sekolah"
                                    class="form-control"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Jenjang
                                </label>

                                <select
                                    name="id_jenjang"
                                    class="form-select"
                                    required>

                                    <option value="">-- Pilih Jenjang --</option>

                                    <?php foreach($jenjang as $j): ?>

                                        <option value="<?= $j['id'] ?>">
                                            <?= $j['kode'] ?> - <?= $j['nama'] ?>
                                        </option>

                                    <?php endforeach ?>

                                </select>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    NPSN
                                </label>

                                <input
                                    type="text"
                                    name="npsn"
                                    class="form-control"
                                    maxlength="20">

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Email Sekolah
                                </label>

                                <input
                                    type="email"
                                    name="email_sekolah"
                                    class="form-control">

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Telepon Sekolah
                                </label>

                                <input
                                    type="text"
                                    name="telepon_sekolah"
                                    class="form-control">

                            </div>

                            <div class="mb-4">

                                <label class="form-label">
                                    Alamat Sekolah
                                </label>

                                <textarea
                                    name="alamat"
                                    rows="4"
                                    class="form-control"></textarea>

                            </div>

                            <div class="d-flex justify-content-between">

                                <button
                                    type="button"
                                    id="btnPrev"
                                    class="btn btn-light">

                                    <i class="fa-solid fa-arrow-left"></i>

                                    Kembali

                                </button>

                                <button
                                    type="submit"
                                    id="btnRegister"
                                    class="btn btn-primary px-5">

                                    <i class="fa-solid fa-user-plus"></i>

                                    Daftar Sekarang

                                </button>

                            </div>

                        </div>

                    </div>

                    <div class="text-center mt-4">

                        Sudah punya akun?

                        <a href="<?= site_url('/') ?>">
                            Masuk
                        </a>
                        <br>

                        <a
                            href="<?= site_url('/') ?>"
                            class="text-decoration-none">
                            <i class="fas fa-arrow-left"></i>
                            Kembali ke Beranda

                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const BASE_URL = "<?= base_url() ?>/";
    </script>

    <script src="<?= base_url('assets/js/register.js') ?>"></script>

    </body>

</html>