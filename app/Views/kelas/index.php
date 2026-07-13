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
                    Tambah Kelas

                </button>

            </div>

        </div>

    </div>

</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">
            Data Kelas
        </h3>
        <small class="text-muted">Kelola data kelas sekolah</small>
        
    </div>

    <div class="card-body">
       <div class="table-responsive">
            <table id="tableKelas"class="table table-striped table-hover table-bordered w-100">
                <thead>
                    <tr>
                        <th width="5%">No</th>

                        <th>Nama Kelas</th>

                        <?php if(session('is_jurusan')): ?>
                            <th>Jurusan</th>
                        <?php endif; ?>

                        <th width="15%">Status</th>

                        <th width="15%">Aksi</th>

                    </tr>
                </thead>
            </table>
       </div>

               <?= view('kelas/modal') ?>

    </div>

</div>
<script>
    const IS_JURUSAN = <?= session('is_jurusan') ? 'true' : 'false' ?>;
</script>
<?= $this->endSection() ?>
