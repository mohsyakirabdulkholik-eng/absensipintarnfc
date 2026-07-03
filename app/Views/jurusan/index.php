<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h3 class="card-title mb-0">

            Data Jurusan

        </h3>
        <small class="text-muted">Kelola data jurusan sekolah</small>

        <button
            class="btn btn-primary"
            id="btnTambah">

            <i class="fa-solid fa-plus"></i>

            Tambah Jurusan

        </button>

    </div>

    <div class="card-body">

       <table
id="tableJurusan"
class="table table-striped table-hover table-bordered w-100">

<thead>

<tr>

<th width="5%">No</th>

<th>Kode</th>

<th>Nama Jurusan</th>

<th width="15%">Aksi</th>

</tr>

</thead>

</table>

<?= view('jurusan/modal') ?>

    </div>

</div>

<?= $this->endSection() ?>