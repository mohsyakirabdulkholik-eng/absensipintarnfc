<div class="modal fade" id="modalGuru" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formGuru" method="POST" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title">Data Guru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
        <div class="modal-body">

            <input type="hidden" id="id" name="id">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Guru <span class="text-danger">*</span></label>
                    <input type="text"
                        class="form-control"
                        id="nama"
                        name="nama"
                        autocomplete="off">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">NIP / NUPTK</label>
                    <input type="text"
                        class="form-control"
                        id="nip"
                        name="nip"
                        autocomplete="off">
                </div>

            </div>

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Jabatan</label>

                    <select class="form-select" id="jabatan" name="jabatan">

                        <option value="">-- Pilih Jabatan --</option>
                        <option value="Guru Kelas">Guru Kelas</option>
                        <option value="Guru Mata Pelajaran">Guru Mata Pelajaran</option>
                        <option value="Wali Kelas">Wali Kelas</option>
                        <option value="Kepala Sekolah">Kepala Sekolah</option>
                        <option value="Wakil Kepala Sekolah">Wakil Kepala Sekolah</option>
                        <option value="Staff TU">Staff TU</option>
                        <option value="Operator">Operator</option>

                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Jenis Kelamin</label>

                    <select class="form-select"
                            id="jenis_kelamin"
                            name="jenis_kelamin">

                        <option value="">-- Pilih --</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>

                    </select>

                </div>

            </div>

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">No HP</label>

                    <input type="text"
                        class="form-control"
                        id="no_hp"
                        name="no_hp">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>

                    <input type="email"
                        class="form-control"
                        id="email"
                        name="email">
                </div>

            </div>

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">Status</label>

                    <select class="form-select"
                            id="status"
                            name="status">

                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>

                    </select>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">Foto</label>

                    <input type="file"
                        class="form-control"
                        id="foto"
                        name="foto">

                </div>

            </div>

            <div class="mb-3">

                <label class="form-label">Alamat</label>

                <textarea class="form-control"
                        id="alamat"
                        name="alamat"
                        rows="3"></textarea>

            </div>

        </div>

       <div class="modal-footer">

            <button
                type="button"
                class="btn btn-secondary"
                data-bs-dismiss="modal">

                Tutup

            </button>

            <button
                type="submit"
                class="btn btn-primary"
                id="btnSimpan">

                <i class="fa-solid fa-floppy-disk"></i>

                Simpan

            </button>

        </div>

            </form>
        </div>
    </div>
</div>
