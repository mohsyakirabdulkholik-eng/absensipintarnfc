<?= $this->extend('layouts/app') ?>
<?= $this->section('content') ?>

<div class="card shadow-sm">

    <div class="card-header d-flex justify-content-between align-items-center">

        <div>
            <h3 class="card-title mb-0 ">
                Data Siswa   
            </h3>
            <small class=" text-muted">
                Kelola data siswa sekolah
            </small>
        </div>

        <button class="btn btn-primary" id="btnTambah">
            <i class="fa-solid fa-plus"></i>
            Tambah Siswa
        </button>

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

                        <th>UID NFC</th>

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