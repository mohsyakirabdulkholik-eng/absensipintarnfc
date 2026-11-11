<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row g-2 align-items-center mb-2">
            <div class="col-sm-6">
                <h3 class="m-0"><?= esc($title) ?></h3>
            </div>
            <div class="col-sm-6 text-end">
                <div class="d-flex flex-wrap gap-2 justify-content-md-start">

                    <a
                        href="<?= site_url('siswa/template') ?>"
                        class="btn btn-success btn-sm-mobile">

                        <i class="fas fa-download"></i>
                        Template

                    </a>

                    <button class="btn btn-info btn-sm-mobile text-white me-2" id="btnImport">

                        <i class="fas fa-file-import"></i>

                        Import Excel

                    </button>

                    <button class="btn btn-primary btn-sm-mobile" id="btnTambah">

                        <i class="fas fa-plus-circle"></i>

                        Tambah Siswa

                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">

     <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">
            Data Siswa
        </h3>
        <small class="text-muted">Kelola data siswa sekolah</small>

    </div>

    <div class="card-body">

    <!-- Filter Kelas -->
        <div class="row mb-3">
            <div class="col-md-4 col-lg-3">

                <label for="filterKelas" class="form-label fw-semibold">
                    Filter Kelas
                </label>

                <select id="filterKelas" class="form-select">
                    <option value="">Semua Kelas</option>
                </select>

            </div>
        </div>

        <div class="table-responsive">

            <table id="tableSiswa"
                class="table table-striped table-hover table-bordered align-middle w-100">

                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="8%">Foto</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Kartu NFC</th>
                        <th>Status</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>

            </table>

        </div>

    </div>

</div>

<?= view('siswa/modal') ?>
<?= view('siswa/modal_import') ?>
<script>
    const base_url = "<?= base_url() ?>";
</script>
<?= $this->endSection() ?>