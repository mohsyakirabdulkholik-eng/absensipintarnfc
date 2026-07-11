<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="content-header">

    <div class="container-fluid">

        <div class="row mb-2">

            <div class="col-sm-6">
                <h3 class="m-0"><?= esc($title) ?></h3>
            </div>

            <div class="col-sm-6 text-end">

                <button
                    class="btn btn-primary"
                    id="btnTambah">

                    <i class="fas fa-plus-circle"></i>
                    Tambah Pengaturan

                </button>

            </div>

        </div>

    </div>

</div>

<section class="content">

    <div class="container-fluid">

        <!-- CARD PENGATURAN AKTIF -->

        <div class="card card-success shadow-sm">

            <div class="card-header">

                <h3 class="card-title">

                    <i class="fas fa-check-circle"></i>

                    Pengaturan Absensi Aktif

                </h3>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-3">

                        <strong>Jam Masuk</strong>

                        <p id="infoJamMasuk">-</p>

                    </div>

                    <div class="col-md-3">

                        <strong>Batas Terlambat</strong>

                        <p id="infoBatasTerlambat">-</p>

                    </div>

                    <div class="col-md-3">

                        <strong>Jam Pulang</strong>

                        <p id="infoJamPulang">-</p>

                    </div>

                    <div class="col-md-3">

                        <strong>Toleransi Pulang</strong>

                        <p id="infoToleransiPulang">-</p>

                    </div>

                </div>

                <hr>

                <strong>Keterangan</strong>

                <p id="infoKeterangan">-</p>

            </div>

        </div>

        <!-- DATATABLE -->

        <div class="card shadow-sm">

            <div class="card-body">

                <div class="table-responsive">

                    <table
                        class="table table-bordered table-striped table-hover w-100"
                        id="tablePengaturan">

                        <thead class="table-light">

                            <tr class="text-center">

                                <th width="5%">No</th>

                                <th>Keterangan</th>

                                <th width="12%">Masuk</th>

                                <th width="12%">Terlambat</th>

                                <th width="12%">Pulang</th>

                                <th width="12%">Toleransi</th>

                                <th width="10%">Status</th>

                                <th width="20%">Aksi</th>

                            </tr>

                        </thead>

                        <tbody>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</section>

<?= $this->include('pengaturanabsensi/modal') ?>

<script>
    const BASE_URL = "<?= base_url() ?>";
</script>

<?= $this->endSection() ?>