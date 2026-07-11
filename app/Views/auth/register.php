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

                        ....
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

                        ....

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

</body>

</html>