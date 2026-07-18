<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="mb-0">Kartu NFC</h2>
            <small class="text-muted">
                Kelola kartu NFC guru dan siswa
            </small>
        </div>
    </div>

    <!-- Card -->
    <div class="card shadow-sm">

        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-0">
                        Data Kartu NFC
                    </h5>
                </div>

                <span class="badge bg-primary">
                    Manajemen Kartu
                </span>
            </div>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table
                    id="tableKartuNfc"
                    class="table table-bordered table-striped align-middle w-100"
                >

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pemilik</th>
                            <th>Jenis</th>
                            <th>UID NFC</th>
                            <th>Nomor Kartu</th>
                            <th>Status</th>
                            <th>Tanggal Daftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


<!-- ========================================= -->
<!-- MODAL DETAIL KARTU NFC -->
<!-- ========================================= -->

<div
    class="modal fade"
    id="modalDetailKartu"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Detail Kartu NFC
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <small class="text-muted">
                        Pemilik
                    </small>

                    <div
                        class="fw-bold"
                        id="detailPemilik"
                    >
                        -
                    </div>
                </div>


                <div class="mb-3">
                    <small class="text-muted">
                        Jenis Pemilik
                    </small>

                    <div id="detailJenis">
                        -
                    </div>
                </div>


                <div class="mb-3">
                    <small class="text-muted">
                        UID NFC
                    </small>

                    <div>
                        <code id="detailUid">
                            -
                        </code>
                    </div>
                </div>


                <div class="mb-3">
                    <small class="text-muted">
                        Nomor Kartu
                    </small>

                    <div id="detailNomorKartu">
                        -
                    </div>
                </div>


                <div class="mb-3">
                    <small class="text-muted">
                        Status
                    </small>

                    <div id="detailStatus">
                        -
                    </div>
                </div>


                <div>
                    <small class="text-muted">
                        Tanggal Daftar
                    </small>

                    <div id="detailTanggal">
                        -
                    </div>
                </div>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Tutup
                </button>

            </div>

        </div>

    </div>
</div>

<script>
    const BASE_URL = "<?= base_url() ?>";
</script>
<?= $this->endSection() ?>
