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
                    Tambah Siswa

                </button>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">

     <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">
            Data Guru
        </h3>
        <small class="text-muted">Kelola data guru sekolah</small>

    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table id="tableSiswa" class="table table-striped table-hover table-bordered align-middle w-100">

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

<?= $this->endSection() ?>