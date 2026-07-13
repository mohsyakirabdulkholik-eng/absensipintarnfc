<div class="modal fade" id="modalKelas" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formKelas" method="POST" action="<?= base_url('kelas/store') ?>">
        <div class="modal-header">
            <h5 class="modal-title">Data Kelas</h5>

            <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>
            <div class="modal-body">

            <input type="hidden" name="id" id="id">
            <?php if(session('is_jurusan')) : ?>
                <div class="mb-3">
                <label>Jurusan</label>

                    <select
                        id="id_jurusan"
                        name="id_jurusan"
                        class="form-select">

                        <option value="">- Tidak Ada -</option>

                    </select>
                </div>
            <?php endif; ?>

        <div class="mb-3">
            <label>Nama Kelas</label>
            <input type="text" id="nama_kelas" name="nama_kelas" class="form-control">
        </div>
        <div class="mb-3">

        <label>Status</label>

            <select name="status"  id="status"  class="form-select">
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select>

        </div>

    </div>

        <div class="modal-footer">
            <button type="button"  class="btn btn-secondary" data-bs-dismiss="modal"> Tutup</button>

            <button type="submit" class="btn btn-primary" id="btnSimpan">
                <i class="fa-solid fa-floppy-disk"></i>
                Simpan
            </button>
        </div>

            </form>
        </div>
    </div>
</div>
