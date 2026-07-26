<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<!-- CONTENT HEADER -->
<div class="content-header">
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-2">
            <h3 class="m-0"><?= esc($title) ?></h3>
                <button
                    type="button"
                    class="btn btn-success btn-sm"
                    id="btnExportExcel"
                >
                
                Download Excel <i class="fas fa-file-excel me-1"></i>
                </button>
        </div>

    </div>
</div>


<!-- FILTER -->
<div class="card shadow-sm mb-3">

    <div class="card-header">
        <h3 class="card-title mb-0">
            Filter Rekap Kehadiran
        </h3>
    </div>

    <div class="card-body">

        <div class="row g-3 align-items-end">

            <!-- JENIS -->
            <div class="col-md-3">

                <label
                    for="filterJenis"
                    class="form-label fw-semibold"
                >
                    Jenis
                </label>

                <select
                    id="filterJenis"
                    class="form-select"
                >
                    <option value="siswa">
                        Siswa
                    </option>

                    <option value="guru">
                        Guru
                    </option>
                </select>

            </div>


            <!-- BULAN -->
            <div class="col-md-3">

                <label
                    for="filterBulan"
                    class="form-label fw-semibold"
                >
                    Bulan
                </label>

                <select
                    id="filterBulan"
                    class="form-select"
                >

                    <?php
                    $bulan = [
                        1  => 'Januari',
                        2  => 'Februari',
                        3  => 'Maret',
                        4  => 'April',
                        5  => 'Mei',
                        6  => 'Juni',
                        7  => 'Juli',
                        8  => 'Agustus',
                        9  => 'September',
                        10 => 'Oktober',
                        11 => 'November',
                        12 => 'Desember'
                    ];

                    $bulanSekarang = (int) date('n');
                    ?>

                    <?php foreach ($bulan as $nomor => $nama): ?>

                        <option
                            value="<?= $nomor ?>"
                            <?= $nomor === $bulanSekarang
                                ? 'selected'
                                : '' ?>
                        >
                            <?= esc($nama) ?>
                        </option>

                    <?php endforeach ?>

                </select>

            </div>


            <!-- TAHUN -->
            <div class="col-md-3">

                <label
                    for="filterTahun"
                    class="form-label fw-semibold"
                >
                    Tahun
                </label>

                <select
                    id="filterTahun"
                    class="form-select"
                >

                    <?php
                    $tahunSekarang = (int) date('Y');

                    for (
                        $tahun = $tahunSekarang;
                        $tahun >= $tahunSekarang - 5;
                        $tahun--
                    ):
                    ?>

                        <option value="<?= $tahun ?>">
                            <?= $tahun ?>
                        </option>

                    <?php endfor ?>

                </select>

            </div>


            <!-- KELAS -->
            <div
                class="col-md-3"
                id="filterKelasContainer"
            >

                <label
                    for="filterKelas"
                    class="form-label fw-semibold"
                >
                    Kelas
                </label>

                <select
                    id="filterKelas"
                    class="form-select"
                >
                    <option value="">
                        Semua Kelas
                    </option>
                </select>

            </div>

        </div>

    </div>

</div>


<!-- STATISTIK -->
<div class="row g-3 mb-3">

    <!-- TOTAL ORANG -->
    <div class="col-6 col-lg-3">

        <div class="card shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div
                            class="text-muted small"
                            id="labelStatTotal"
                        >
                            Total Siswa
                        </div>

                        <h3
                            class="mb-0 fw-bold"
                            id="statTotal"
                        >
                            0
                        </h3>

                    </div>

                    <div class="fs-2 text-primary">
                        <i class="fas fa-users"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- HARI EFEKTIF -->
    <div class="col-6 col-lg-3">

        <div class="card shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="text-muted small">
                            Hari Efektif
                        </div>

                        <h3
                            class="mb-0 fw-bold"
                            id="statHariEfektif"
                        >
                            0
                        </h3>

                    </div>

                    <div class="fs-2 text-info">
                        <i class="fas fa-calendar-check"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- PERSENTASE -->
    <div class="col-6 col-lg-3">

        <div class="card shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="text-muted small">
                            Kehadiran
                        </div>

                        <h3
                            class="mb-0 fw-bold text-success"
                            id="statPersentase"
                        >
                            0%
                        </h3>

                    </div>

                    <div class="fs-2 text-success">
                        <i class="fas fa-chart-line"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- TERLAMBAT -->
    <div class="col-6 col-lg-3">

        <div class="card shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="text-muted small">
                            Terlambat
                        </div>

                        <h3
                            class="mb-0 fw-bold text-warning"
                            id="statTerlambat"
                        >
                            0
                        </h3>

                    </div>

                    <div class="fs-2 text-warning">
                        <i class="fas fa-clock"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- TABLE -->
<div class="card shadow-sm">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h3
            class="card-title mb-0"
            id="judulTabel"
        >
            Rekap Kehadiran Siswa
        </h3>

        <small
            class="text-muted"
            id="periodeTabel"
        ></small>

    </div>


    <div class="card-body">

        <div class="table-responsive">

            <table
                id="tableRekapKehadiran"
                class="table table-striped table-hover table-bordered align-middle w-100"
            >

                <thead class="table-light">

                    <tr>

                        <th width="5%">
                            No
                        </th>

                        <th>
                            Identitas
                        </th>

                        <th>
                            Nama
                        </th>

                        <th id="headerKelas">
                            Kelas
                        </th>

                        <th>
                            Hari Efektif
                        </th>

                        <th>
                            Hadir
                        </th>

                        <th>
                            Terlambat
                        </th>

                        <th>
                            Tidak Hadir
                        </th>

                        <th>
                            Persentase
                        </th>

                        <th width="8%">
                            Aksi
                        </th>

                    </tr>

                </thead>

            </table>

        </div>

    </div>

</div>
<?= $this->include('rekap_kehadiran/modal') ?>
<?= $this->endSection() ?>

