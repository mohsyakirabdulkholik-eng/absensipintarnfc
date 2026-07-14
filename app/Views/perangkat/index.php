<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0"></h3>
                </div>
                <div class="col-sm-6 text-end">
                    <button class="btn btn-primary" id="btnTambah">
                        <i class="fas fa-plus-circle"></i>
                        Tambah Perangkat
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">

                    <h3 class="card-title">
                        <i class="fas fa-mobile-alt me-2"></i>
                        Data Perangkat
                    </h3>

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table
                        class="table table-bordered table-hover table-striped w-100"
                        id="tablePerangkat">

                            <thead>

                                <tr>

                                    <th width="5%">No</th>

                                    <th>Nama Perangkat</th>

                                    <th>Merk</th>

                                    <th>Model</th>

                                    <th>Android</th>

                                    <th>Status</th>

                                    <th>Last Sync</th>

                                    <th width="15%">Aksi</th>

                                </tr>

                            </thead>

                            <tbody>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>
    </div>

</div>

<?= $this->include('perangkat/modal_form') ?>
<?= $this->include('perangkat/modal_token') ?>
<?= $this->include('perangkat/modal_detail') ?>

<script>
    const BASE_URL = "<?= base_url() ?>";
</script>

<?= $this->endSection() ?>