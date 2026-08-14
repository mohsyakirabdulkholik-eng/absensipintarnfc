<?= $this->extend('layouts/superadmin') ?>

<?= $this->section('content') ?>

<div class="container-fluid sekolah-detail">

    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                Detail Sekolah
            </h3>

            <p class="text-muted mb-0">
                Informasi lengkap sekolah dan administrator.
            </p>
        </div>

        <a
            href="<?= base_url('superadmin/sekolah') ?>"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>

    </div>


    <!-- Informasi Utama -->
    <div class="row g-3">

        <!-- Profil Sekolah -->
        <div class="col-12 col-xl-7">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        <i class="bi bi-building me-2"></i>
                        Informasi Sekolah
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row mb-3">

                        <div class="col-sm-4 text-muted">
                            Nama Sekolah
                        </div>

                        <div class="col-sm-8 fw-semibold">
                            <?= esc($sekolah['nama_sekolah']) ?>
                        </div>

                    </div>


                    <div class="row mb-3">

                        <div class="col-sm-4 text-muted">
                            Jenjang
                        </div>

                        <div class="col-sm-8">
                            <?= esc($sekolah['nama_jenjang'] ?? '-') ?>
                        </div>

                    </div>


                    <div class="row mb-3">

                        <div class="col-sm-4 text-muted">
                            NPSN
                        </div>

                        <div class="col-sm-8">
                            <?= esc($sekolah['npsn'] ?? '-') ?>
                        </div>

                    </div>


                    <div class="row mb-3">

                        <div class="col-sm-4 text-muted">
                            Email
                        </div>

                        <div class="col-sm-8">
                            <?= esc($sekolah['email'] ?? '-') ?>
                        </div>

                    </div>


                    <div class="row mb-3">

                        <div class="col-sm-4 text-muted">
                            Telepon
                        </div>

                        <div class="col-sm-8">
                            <?= esc($sekolah['telepon'] ?? '-') ?>
                        </div>

                    </div>


                    <div class="row mb-3">

                        <div class="col-sm-4 text-muted">
                            Alamat
                        </div>

                        <div class="col-sm-8">
                            <?= nl2br(
                                esc($sekolah['alamat'] ?? '-')
                            ) ?>
                        </div>

                    </div>


                    <div class="row">

                        <div class="col-sm-4 text-muted">
                            Status
                        </div>

                        <div class="col-sm-8">

                            <?php if ($sekolah['status'] === 'aktif'): ?>

                                <span class="badge text-bg-success">
                                    Aktif
                                </span>

                            <?php else: ?>

                                <span class="badge text-bg-secondary">
                                    <?= esc(
                                        ucfirst($sekolah['status'])
                                    ) ?>
                                </span>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Admin Sekolah -->
        <div class="col-12 col-xl-5">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        <i class="bi bi-person-badge me-2"></i>
                        Admin Sekolah
                    </h5>

                </div>

                <div class="card-body">

                    <?php if (!empty($sekolah['nama_admin'])): ?>

                        <div class="text-center mb-4">

                            <div
                                class="rounded-circle bg-primary-subtle
                                       d-inline-flex align-items-center
                                       justify-content-center"
                                style="width: 72px; height: 72px;"
                            >
                                <i class="bi bi-person fs-2 text-primary"></i>
                            </div>

                            <h5 class="mt-3 mb-1">
                                <?= esc($sekolah['nama_admin']) ?>
                            </h5>

                            <p class="text-muted mb-0">
                                Admin Sekolah
                            </p>

                        </div>


                        <div class="border-top pt-3">

                            <div class="mb-3">

                                <small class="text-muted d-block">
                                    Username
                                </small>

                                <span>
                                    @<?= esc(
                                        $sekolah['username_admin']
                                    ) ?>
                                </span>

                            </div>


                            <div>

                                <small class="text-muted d-block">
                                    Email
                                </small>

                                <span>
                                    <?= esc(
                                        $sekolah['email_admin'] ?? '-'
                                    ) ?>
                                </span>

                            </div>

                        </div>

                    <?php else: ?>

                        <div class="text-center text-muted py-4">

                            <i class="bi bi-person-x fs-1 d-block mb-2"></i>

                            <p class="mb-0">
                                Belum ada Admin Sekolah.
                            </p>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>


    <!-- Langganan -->
    <div class="card shadow-sm border-0 mt-3">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                <i class="bi bi-credit-card me-2"></i>
                Langganan
            </h5>

        </div>

        <div class="card-body">

            <?php if (!$langganan): ?>

                <!-- Belum ada langganan -->

                <div class="d-flex flex-wrap justify-content-between align-items-center">

                    <div>

                        <h5 class="mb-1">
                            Belum Berlangganan
                        </h5>

                        <p class="text-muted mb-0">
                            Sekolah ini belum memiliki langganan.
                        </p>

                    </div>

                    <div class="mt-3 mt-md-0">

                        <button
                            type="button"
                            class="btn btn-primary"
                            id="btnAktifkanPaket"
                            data-id-sekolah="<?= esc($sekolah['id']) ?>"
                            data-id-paket="<?= esc($paketAktif[0]['id'] ?? '') ?>"
                        >
                            <i class="bi bi-credit-card me-1"></i>
                            Aktifkan Paket
                        </button>

                    </div>

                </div>


            <?php else: ?>

                <!-- Sudah memiliki langganan -->

                <div class="row g-4">

                    <div class="col-md-8">

                        <h5 class="mb-3">
                            <?= esc(
                                $paket['nama_paket'] ?? 'Paket'
                            ) ?>
                        </h5>


                        <div class="row g-3">

                            <div class="col-sm-6">

                                <small class="text-muted d-block">
                                    Harga
                                </small>

                                <strong>
                                    Rp<?= number_format(
                                        (float) ($paket['harga'] ?? 0),
                                        0,
                                        ',',
                                        '.'
                                    ) ?>
                                </strong>

                            </div>


                            <div class="col-sm-6">

                                <small class="text-muted d-block">
                                    Durasi
                                </small>

                                <strong>
                                    <?= esc(
                                        $paket['durasi_hari'] ?? 0
                                    ) ?>
                                    hari
                                </strong>

                            </div>


                            <div class="col-sm-6">

                                <small class="text-muted d-block">
                                    Tanggal Mulai
                                </small>

                                <strong>
                                    <?= date(
                                        'd-m-Y',
                                        strtotime(
                                            $langganan['tanggal_mulai']
                                        )
                                    ) ?>
                                </strong>

                            </div>


                            <div class="col-sm-6">

                                <small class="text-muted d-block">
                                    Tanggal Berakhir
                                </small>

                                <strong>
                                    <?= date(
                                        'd-m-Y',
                                        strtotime(
                                            $langganan['tanggal_berakhir']
                                        )
                                    ) ?>
                                </strong>

                            </div>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="text-md-end">

                            <?php if ($aktif): ?>

                                <span class="badge text-bg-success mb-2">
                                    Aktif
                                </span>

                                <div class="fs-4 fw-semibold">
                                    <?= $sisaHari ?> hari
                                </div>

                                <small class="text-muted d-block mb-3">
                                    Sisa masa langganan
                                </small>

                                <button
                                    type="button"
                                    class="btn btn-outline-primary btn-sm"
                                    id="btnPerpanjangPaket"
                                    data-id-sekolah="<?= esc($sekolah['id']) ?>"
                                    data-id-paket="<?= esc($paket['id']) ?>"
                                >
                                    <i class="bi bi-arrow-repeat me-1"></i>
                                    Perpanjang
                                </button>

                            <?php else: ?>

                                <span class="badge text-bg-danger mb-2">
                                    Expired
                                </span>

                                <div class="text-muted">
                                    Masa langganan telah berakhir.
                                </div>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>
<script>
    const BASE_URL = '<?= base_url() ?>';
</script>

<?= $this->endSection() ?>