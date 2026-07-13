<nav class="app-header navbar navbar-expand bg-primary navbar-dark">

    <div class="container-fluid">

        <!-- Tombol Sidebar -->
        <ul class="navbar-nav">

            <li class="nav-item">

                <a class="nav-link"
                   data-lte-toggle="sidebar"
                   href="#"
                   role="button">

                    <i class="fas fa-bars"></i>

                </a>

            </li>

        </ul>

        <!-- Judul Aplikasi -->
        <a href="<?= site_url('dashboard') ?>" class="navbar-brand">

            Absensi Pintar

        </a>

        <!-- Menu User -->
        <ul class="navbar-nav ms-auto">

            <li class="nav-item dropdown">

                <a class="nav-link"
                   data-bs-toggle="dropdown"
                   href="#">

                    <i class="fa-solid fa-circle-user me-1"></i>

                    <?= session('nama') ?>

                </a>

                <ul class="dropdown-menu dropdown-menu-end">

                    <li class="dropdown-header text-center">

                        <strong><?= session('nama') ?></strong><br>

                        <small><?= session('nama_role') ?></small>

                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>

                        <a class="dropdown-item"
                           href="<?= site_url('profile') ?>">

                            <i class="fa-solid fa-user me-2"></i>

                            Profil Saya

                        </a>

                    </li>

                    <li>

                        <a class="dropdown-item"
                           href="<?= site_url('profile/password') ?>">

                            <i class="fa-solid fa-key me-2"></i>

                            Ubah Password

                        </a>

                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>

                        <a class="dropdown-item text-danger btn-logout"
                           href="javascript:void(0)">

                            <i class="fa-solid fa-right-from-bracket me-2"></i>

                            Logout

                        </a>

                    </li>

                </ul>

            </li>

        </ul>

    </div>

</nav>
