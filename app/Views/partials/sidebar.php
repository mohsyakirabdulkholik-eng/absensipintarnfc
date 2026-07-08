<aside class="app-sidebar bg-body-secondary shadow">

    <div class="sidebar-brand">

        <a href="/dashboard"
           class="brand-link">

            <span class="brand-text">

                Absensi Pintar

            </span>

        </a>

    </div>

    <div class="sidebar-wrapper">
               
        <nav>
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">
                
                <li class="nav-item">
                    <a href="<?= site_url('dashboard') ?>"
                       class="nav-link <?= menu_active('dashboard') ?>">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('jurusan') ?>" class="nav-link">
                        <i class="fa-solid fa-chalkboard-user"></i>
                        <p>Jurusan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('kelas') ?>" class="nav-link">
                        <i class="fa-solid fa-chalkboard-user"></i>
                        <p>Kelas</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('guru') ?>" class="nav-link">
                        <i class="fa-solid fa-chalkboard-user"></i>
                        <p>Guru</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('siswa') ?>" class="nav-link">
                        <i class="fa-solid fa-chalkboard-user"></i>
                        <p>Siswa</p>
                    </a>
                </li>
            </ul>
        </nav>

    </div>

</aside>