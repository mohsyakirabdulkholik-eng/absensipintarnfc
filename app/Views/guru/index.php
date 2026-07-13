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
                    Tambah Guru

                </button>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">
            Data Guru
        </h3>
        <small class="text-muted">Kelola data guru sekolah</small>

    </div>

    <div class="card-body">
       <table id="tableGuru"class="table table-striped table-hover table-bordered w-100">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Guru</th>
                    <th>NIP/NUPTK</th>
                    <th>Jabatan</th>
                    <th>Status</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
        </table>

<?= view('guru/modal') ?>

    </div>

</div>

<?= $this->endSection() ?>
