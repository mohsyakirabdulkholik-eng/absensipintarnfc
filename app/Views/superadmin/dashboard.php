<?= $this->extend('layouts/superadmin') ?>

<?= $this->section('content') ?>

<div class="container-fluid superadmin-dashboard">

    <!-- Header -->
    <div class="dashboard-header mb-4">

        <h3 class="mb-1">
            Dashboard Superadmin
        </h3>

        <p class="text-muted mb-0">
            Selamat datang,
            <?= esc(session()->get('nama')) ?>.
        </p>

    </div>


    <!-- Statistik -->
    <div class="row g-3 stat-row">

        <!-- Total Sekolah -->
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="card h-100 shadow-sm border-0 stat-card">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1 stat-label">
                                Total Sekolah
                            </p>

                            <h2 class="mb-0 stat-value">
                                <?= esc($data['totalSekolah']) ?>
                            </h2>

                        </div>

                        <div class="fs-1 text-primary stat-icon">
                            <i class="fas fa-building"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Sekolah Aktif -->
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="card h-100 shadow-sm border-0 stat-card">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1 stat-label">
                                Sekolah Aktif
                            </p>

                            <h2 class="mb-0 stat-value">
                                <?= esc($data['sekolahAktif']) ?>
                            </h2>

                        </div>

                        <div class="fs-1 text-success stat-icon">
                            <i class="fas fa-building-circle-check"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Langganan Aktif -->
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="card h-100 shadow-sm border-0 stat-card">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1 stat-label">
                                Langganan Aktif
                            </p>

                            <h2 class="mb-0 stat-value">
                                <?= esc($data['langgananAktif']) ?>
                            </h2>

                        </div>

                        <div class="fs-1 text-success stat-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Expired -->
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="card h-100 shadow-sm border-0 stat-card">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1 stat-label">
                                Langganan Expired
                            </p>

                            <h2 class="mb-0 stat-value">
                                <?= esc($data['langgananExpired']) ?>
                            </h2>

                        </div>

                        <div class="fs-1 text-danger stat-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- Konten bawah -->
    <div class="row g-3 mt-1 content-row">

        <!-- Sekolah Terbaru -->
        <div class="col-12 col-xl-7">

            <div class="card shadow-sm border-0 h-100 dashboard-card">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Sekolah Terbaru
                    </h5>

                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0 school-table">

                            <thead>

                                <tr>
                                    <th>Nama Sekolah</th>
                                    <th>Jenjang</th>
                                    <th>Status</th>
                                </tr>

                            </thead>

                            <tbody>

                                <?php if (!empty($data['sekolahTerbaru'])): ?>

                                    <?php foreach ($data['sekolahTerbaru'] as $sekolah): ?>

                                        <tr>

                                            <td>
                                                <?= esc($sekolah['nama_sekolah']) ?>
                                            </td>

                                            <td>
                                                <?= esc($sekolah['nama_jenjang'] ?? '-') ?>
                                            </td>

                                            <td>

                                                <?php if ($sekolah['status'] === 'aktif'): ?>

                                                    <span class="badge text-bg-success">
                                                        Aktif
                                                    </span>

                                                <?php else: ?>

                                                    <span class="badge text-bg-secondary">
                                                        <?= esc(ucfirst($sekolah['status'])) ?>
                                                    </span>

                                                <?php endif; ?>

                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <tr>

                                        <td
                                            colspan="3"
                                            class="text-center text-muted py-4"
                                        >
                                            Belum ada sekolah.
                                        </td>

                                    </tr>

                                <?php endif; ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        <!-- Akan Berakhir -->
        <div class="col-12 col-xl-5">

            <div class="card shadow-sm border-0 h-100 dashboard-card">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        Langganan Akan Berakhir
                    </h5>

                </div>

                <div class="card-body">

                    <?php if (!empty($data['langgananAkanBerakhir'])): ?>

                        <?php foreach ($data['langgananAkanBerakhir'] as $langganan): ?>

                            <?php
                                $today = new DateTime(date('Y-m-d'));
                                $end   = new DateTime($langganan['tanggal_berakhir']);

                                $sisaHari = $today->diff($end)->days;
                            ?>

                            <div class="d-flex justify-content-between align-items-center border-bottom py-3 subscription-item">

                                <div>

                                    <div class="fw-semibold school-name">
                                        <?= esc($langganan['nama_sekolah']) ?>
                                    </div>

                                    <small class="text-muted">
                                        <?= esc($langganan['nama_paket'] ?? 'Premium') ?>
                                    </small>

                                </div>

                                <div class="text-end subscription-date">

                                    <div class="small text-muted">
                                        <?= date(
                                            'd-m-Y',
                                            strtotime($langganan['tanggal_berakhir'])
                                        ) ?>
                                    </div>

                                    <span class="badge text-bg-warning">
                                        <?= $sisaHari ?> hari
                                    </span>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <div class="text-center text-muted py-4 empty-state">

                            <i class="bi bi-check-circle fs-2 d-block mb-2"></i>

                            Tidak ada langganan yang akan berakhir
                            dalam 7 hari.

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>

</div>
<script>
    const BASE_URL = '<?= base_url() ?>';
</script>

<?= $this->endSection() ?>