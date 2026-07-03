<div class="modal fade" id="modalJurusan" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formJurusan">
        <div class="modal-header">
            <h5 class="modal-title">Data Jurusan</h5>

            <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>
            <div class="modal-body">

            <input type="hidden" id="id">
            <div class="mb-3">
                <label>Kode</label>

            <input type="text" id="kode" name="kode" class="form-control">
        </div>

        <div class="mb-3">
            <label>Nama Jurusan</label>
            <input type="text" id="nama_jurusan" name="nama_jurusan" class="form-control">
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
