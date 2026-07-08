<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">
            Data Kelas
        </h3>
        <small class="text-muted">Kelola data kelas sekolah</small>
        <button class="btn btn-primary" id="btnTambah">
            <i class="fa-solid fa-plus"></i> Tambah Kelas
        </button>
    </div>

    <div class="card-body">
       <div class="table-responsive">
            <table id="tableKelas"class="table table-striped table-hover table-bordered w-100">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Jurusan</th>
                        <th>Nama Kelas</th>
                        <th width="15%">Status</th>
                    </tr>
                </thead>
            </table>
       </div>

               <?= view('kelas/modal') ?>

    </div>

</div>

<?= $this->endSection() ?>
