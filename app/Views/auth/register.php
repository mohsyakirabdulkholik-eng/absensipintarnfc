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

</head>

<body class="bg-body-secondary">

<div class="container py-5">

    <div class="text-center mb-4">

        <h2 class="fw-bold">

            Smart Absensi NFC

        </h2>

        <p class="text-muted">

            Sistem Absensi Sekolah Berbasis NFC

        </p>

    </div>

    <form id="formRegister">

        <?= csrf_field() ?>

        <div class="row">

            <!-- DATA ADMIN -->

            <div class="col-lg-6">

                <div class="card card-primary">

                    <div class="card-header">

                        <h5 class="mb-0">

                            Data Administrator

                        </h5>

                    </div>

                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Administrator</label>
                            <input type="text"
                                name="nama"
                                class="form-control"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text"
                                name="username"
                                class="form-control"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email"
                                name="email"
                                class="form-control"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor HP</label>
                            <input type="text"
                                name="telepon"
                                class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>

                            <div class="input-group">

                                <input type="password"
                                    name="password"
                                    id="password"
                                    class="form-control"
                                    minlength="8"
                                    required>

                                <button class="btn btn-outline-secondary"
                                        type="button"
                                        id="togglePassword">

                                    <i class="fa-solid fa-eye"></i>

                                </button>

                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password</label>

                            <div class="input-group">

                                <input type="password"
                                    name="konfirmasi_password"
                                    id="konfirmasiPassword"
                                    class="form-control"
                                    minlength="8"
                                    required>

                                <button class="btn btn-outline-secondary"
                                        type="button"
                                        id="toggleKonfirmasi">

                                    <i class="fa-solid fa-eye"></i>

                                </button>

                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <!-- DATA SEKOLAH -->

            <div class="col-lg-6">

                <div class="card card-success">

                    <div class="card-header">

                        <h5 class="mb-0">

                            Data Sekolah

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <label class="form-label">

                                Nama Sekolah

                            </label>

                            <input type="text"
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

                                <option value="">

                                    -- Pilih Jenjang --

                                </option>

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

                        <div class="mb-3">

                            <label class="form-label">

                                Alamat Sekolah

                            </label>

                            <textarea
                                name="alamat"
                                rows="4"
                                class="form-control"></textarea>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="text-center mt-4">

            <button
                class="btn btn-primary btn-lg px-5">

                <i class="fa-solid fa-user-plus"></i>

                Daftar Sekarang

            </button>

        </div>

    </form>

    <div class="text-center mt-3">

        Sudah punya akun?

        <a href="<?= site_url('/') ?>">

            Masuk

        </a>

    </div>

</div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script> const BASE_URL = "<?= base_url() ?>/"; </script>
    <script src="<?= base_url('assets/js/register.js')?>" ></script>
</body>

</html>