<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3 class="m-0"><?= esc($title) ?></h3>
            </div>
            <div class="col-sm-6 text-end">
                <button class="btn btn-primary" id="btnTambah">
                    <i class="fas fa-plus-circle"></i>
                    Tambah Semester
                </button>
            </div>
        </div>
    </div>
</div>

<section class="content">

    <div class="container-fluid">

        <div class="card shadow-sm">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-striped table-hover w-100" id="tableSemester">
                        
                       <thead class="table-light">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>Tahun Ajaran</th>
                                <th width="20%">Semester</th>
                                <th width="15%">Status</th>
                                <th width="25%">Aksi</th>
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

<?= $this->include('semester/modal') ?>

<script>
    const BASE_URL = "<?= base_url() ?>";
</script>

<?= $this->endSection() ?>