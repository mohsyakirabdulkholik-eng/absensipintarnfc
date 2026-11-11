<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="light">

    <!-- Brand -->
    <div class="sidebar-brand">

        <a href="<?= base_url('superadmin') ?>"
           class="brand-link text-decoration-none">

            <span class="brand-text fw-light">
                Absensi Pintar
            </span>

        </a>

    </div>

    <!-- Sidebar -->
    <div class="sidebar-wrapper">

        <nav class="mt-2">

            <ul
                class="nav sidebar-menu flex-column"
                data-lte-toggle="treeview"
                role="menu"
                data-accordion="false"
            >

                <!-- Dashboard -->
                <li class="nav-item">

                    <a
                        href="<?= base_url('superadmin') ?>"
                        class="nav-link <?= uri_string() === 'superadmin' ? 'active' : '' ?>"
                    >

                        <i class="nav-icon fas fa-gauge"></i>

                        <p>
                            Dashboard
                        </p>

                    </a>

                </li>


                <!-- SEKOLAH -->
                <li class="nav-header">
                    SEKOLAH
                </li>


                <!-- Daftar Sekolah -->
                <li class="nav-item">

                    <a
                        href="<?= base_url('superadmin/sekolah') ?>"
                        class="nav-link"
                    >

                        <i class="nav-icon fas fa-building"></i>

                        <p>
                            Daftar Sekolah
                        </p>

                    </a>

                </li>


                <!-- LANGGANAN -->
                <li class="nav-header">
                    LANGGANAN
                </li>


                <!-- Langganan -->
                <li class="nav-item">

                    <a
                        href="<?= base_url('superadmin/langganan') ?>"
                        class="nav-link"
                    >

                        <i class="nav-icon fas fa-credit-card"></i>

                        <p>
                            Langganan
                        </p>

                    </a>

                </li>


                <!-- Pembayaran -->
                <li class="nav-item">

                    <a
                        href="<?= base_url('superadmin/pembayaran') ?>"
                        class="nav-link"
                    >

                        <i class="nav-icon fas fa-receipt"></i>

                        <p>
                            Pembayaran
                        </p>

                    </a>

                </li>


                <!-- SISTEM -->
                <li class="nav-header">
                    SISTEM
                </li>


                <!-- Pengaturan Platform -->
                <li class="nav-item">

                    <a
                        href="<?= base_url('superadmin/pengaturan') ?>"
                        class="nav-link"
                    >

                        <i class="nav-icon fas fa-gear"></i>

                        <p>
                            Pengaturan Platform
                        </p>

                    </a>

                </li>


                <!-- Logout -->
                <li class="nav-item mt-3">

                    <a
                        href="<?= base_url('logout') ?>"
                        class="nav-link"
                    >

                        <i class="nav-icon fas fa-right-from-bracket"></i>

                        <p>
                            Logout
                        </p>

                    </a>

                </li>

            </ul>

        </nav>

    </div>

</aside>