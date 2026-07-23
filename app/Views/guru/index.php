<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>
<div class="content-header">
    <div class="container-fluid">

        <div class="row g-2 align-items-center mb-2">

            <div class="col-12 col-md-4">
                <h3 class="m-0"><?= esc($title) ?></h3>
            </div>

            <div class="col-12 col-md-8">

                <div class="d-flex flex-wrap gap-2 justify-content-md-start">

                    <a
                        href="<?= site_url('guru/template') ?>"
                        class="btn btn-success btn-sm-mobile">

                        <i class="fas fa-download"></i>
                        <span>Template</span>

                    </a>

                    <button
                        type="button"
                        class="btn btn-info btn-sm-mobile"
                        id="btnImport">

                        <i class="fas fa-file-import"></i>
                        <span>Import Excel</span>

                    </button>

                    <button
                        type="button"
                        class="btn btn-primary btn-sm-mobile"
                        id="btnTambah">

                        <i class="fas fa-plus-circle"></i>
                        <span>Tambah Guru</span>

                    </button>

                </div>

            </div>

        </div>

    </div>
</div>

<div class="card shadow-sm">

    <div class="card-header">

        <div class="d-flex flex-column flex-md-row
                    justify-content-between
                    align-items-md-center
                    gap-1">

            <h3 class="card-title mb-0">
                Data Guru
            </h3>

            <small class="text-muted">
                Kelola data guru sekolah
            </small>

        </div>

    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table
                id="tableGuru"
                class="table table-striped table-hover table-bordered align-middle w-100">
                <thead>
                    <tr>

                        <th width="5%">No</th>

                        <th>Nama</th>

                        <th>NIP/NUPTK</th>

                        <th>Jabatan</th>

                        <th>Status</th>

                        <th width="15%">Aksi</th>

                    </tr>
                </thead>
                <tbody>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?= view('guru/modal') ?>
<?= view('guru/modal_import') ?>



<?= $this->endSection() ?>
