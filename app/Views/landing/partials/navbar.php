<nav class="navbar navbar-expand-lg landing-navbar fixed-top">

    <div class="container">

        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="<?= base_url('/') ?>">

            <img src="<?= base_url('assets/logo/logo.png') ?>"
                 class="logo"
                 alt="Logo">

            <div class="ms-2">

                <h5 class="logo-title mb-0">
                    Absensi Pintar
                </h5>

                <small class="logo-subtitle">
                    NFC Platform
                </small>

            </div>

        </a>

        <!-- Mobile Button -->
        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#landingNavbar">

            <span class="navbar-toggler-icon"></span>

        </button>

        <!-- Menu -->
        <div class="collapse navbar-collapse"
             id="landingNavbar">

            <ul class="navbar-nav mx-auto">

                <li class="nav-item">
                    <a class="nav-link active" href="#">
                        Beranda
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        Fitur
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        Cara Kerja
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        FAQ
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        Kontak
                    </a>
                </li>

            </ul>

            <div class="d-flex">

                <a href="<?= site_url('login') ?>"
                   class="btn btn-login">

                    Masuk

                </a>

                <a href="<?= site_url('register') ?>"
                   class="btn btn-register">

                    Daftar Sekolah

                </a>

            </div>

        </div>

    </div>

</nav>