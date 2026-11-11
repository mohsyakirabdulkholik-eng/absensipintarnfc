<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="content-header">
    <div class="container-fluid">

        <div class="row g-2 align-items-center mb-2">

            <div class="col-sm-6">
                <h3 class="m-0"><?= esc($title) ?></h3>
            </div>

        </div>

    </div>
</div>


<!-- FILTER -->
<div class="card shadow-sm mb-3">

    <div class="card-header">
        <h3 class="card-title mb-0">
            Filter Kehadiran
        </h3>
    </div>

    <div class="card-body">

        <div class="row g-3 align-items-end">

            <!-- Tanggal -->
            <div class="col-md-4 col-lg-3">

                <label for="filterTanggal"
                       class="form-label fw-semibold">
                    Tanggal
                </label>

                <input
                    type="date"
                    id="filterTanggal"
                    class="form-control"
                    value="<?= date('Y-m-d') ?>"
                >

            </div>


            <!-- Jenis -->
            <div class="col-md-4 col-lg-3">

                <label for="filterJenis"
                       class="form-label fw-semibold">
                    Jenis Kehadiran
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


            <!-- Kelas -->
            <div
                class="col-md-4 col-lg-3"
                id="filterKelasContainer"
            >

                <label for="filterKelas"
                       class="form-label fw-semibold">
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

    <!-- TOTAL -->
    <div class="col-6 col-lg-3">

        <div class="card shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <div class="text-muted small">
                            Total
                        </div>

                        <h3
                            class="mb-0 fw-bold"
                            id="statTotal"
                        >
                            0
                        </h3>
                    </div>

                    <div class="fs-2 text-secondary">
                        <i class="fas fa-users"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- HADIR -->
    <div class="col-6 col-lg-3">

        <div class="card shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <div class="text-muted small">
                            Hadir
                        </div>

                        <h3
                            class="mb-0 fw-bold text-success"
                            id="statHadir"
                        >
                            0
                        </h3>
                    </div>

                    <div class="fs-2 text-success">
                        <i class="fas fa-circle-check"></i>
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


    <!-- BELUM HADIR -->
    <div class="col-6 col-lg-3">

        <div class="card shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <div class="text-muted small">
                            Belum Hadir
                        </div>

                        <h3
                            class="mb-0 fw-bold text-danger"
                            id="statBelumHadir"
                        >
                            0
                        </h3>
                    </div>

                    <div class="fs-2 text-danger">
                        <i class="fas fa-user-clock"></i>
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
            Kehadiran Siswa
        </h3>

        <small
            class="text-muted"
            id="tanggalTabel"
        ></small>

    </div>


    <div class="card-body">

        <div class="table-responsive">

            <table
                id="tableKehadiran"
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
                            Jam Masuk
                        </th>

                        <th>
                            Jam Pulang
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Keterangan
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

<?= $this->endSection() ?>