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

                <?php if(session('is_jurusan')) : ?>

                    <li class="nav-item">

                        <a href="<?= base_url('jurusan') ?>"
                        class="nav-link <?= menu_active('jurusan') ?>">

                            <i class="fa-solid fa-building-columns"></i>

                            <p>Jurusan</p>

                        </a>

                    </li>

                <?php endif; ?>

                <li class="nav-item">
                    <a href="<?= base_url('kelas') ?>" class="nav-link">
                        <i class="nav-icon fa-solid fa-school"></i>
                        <p>Kelas</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('guru') ?>" class="nav-link">
                        <i class="nav-icon fa-solid fa-chalkboard-user"></i>
                        <p>Guru</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('siswa') ?>" class="nav-link">
                        <i class="nav-icon fa-solid fa-user-graduate"></i>
                        <p>Siswa</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('tahunajaran') ?>" class="nav-link">
                        <i class="nav-icon fa-solid fa-calendar-days"></i>
                        <p>Tahun Ajaran</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('semester') ?>" class="nav-link">
                        <i class="nav-icon fa-solid fa-calendar-week"></i>
                        <p>Semester</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('harilibur') ?>" class="nav-link">
                        <i class="nav-icon fa-solid fa-calendar-xmark"></i>
                        <p>Hari Libur</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('pengaturanabsensi') ?>" class="nav-link">
                        <i class="nav-icon fa-solid fa-clock"></i>
                        <p>Pengaturan Absensi</p>
                    </a>
                </li>
                <li class="nav-item">

                    <a href="<?= site_url('setting-sekolah') ?>"
                    class="nav-link <?= menu_active('setting-sekolah') ?>">

                        <i class="nav-icon fa-solid fa-school"></i>

                        <p>Pengaturan Sekolah</p>

                    </a>

                </li>
                <li class="nav-item">
                    <a href="<?= base_url('perangkat') ?>"
                    class="nav-link <?= uri_string() == 'perangkat' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-mobile-alt"></i>
                        <p>Perangkat</p>
                    </a>
                </li>
            </ul>
        </nav>

    </div>

</aside>