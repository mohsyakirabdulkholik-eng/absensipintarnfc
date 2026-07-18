<div class="modal fade" id="modalSiswa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form id="formSiswa" enctype="multipart/form-data">

                <div class="modal-header">
                    <h5 class="modal-title">
                        Data Siswa
                    </h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="id" name="id">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                NIS <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="nis"
                                name="nis"
                                autocomplete="off">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                NISN
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="nisn"
                                name="nisn"
                                autocomplete="off">
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Nama Siswa <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="nama_siswa"
                                name="nama_siswa">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Jenis Kelamin
                            </label>

                            <select
                                class="form-select"
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

                            <label class="form-label">
                                Tempat Lahir
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="tempat_lahir"
                                name="tempat_lahir">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Tanggal Lahir
                            </label>

                            <input
                                type="date"
                                class="form-control"
                                id="tanggal_lahir"
                                name="tanggal_lahir">

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Kelas
                            </label>

                            <select
                                class="form-select"
                                id="id_kelas"
                                name="id_kelas">

                                <option value="">-- Pilih Kelas --</option>

                            </select>

                        </div>

                   </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Nama Orang Tua
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="nama_ortu"
                                name="nama_ortu">

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                No HP
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="no_hp"
                                name="no_hp">

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Status
                            </label>

                            <select
                                class="form-select"
                                id="status"
                                name="status">

                                <option value="aktif">Aktif</option>
                                <option value="lulus">Lulus</option>
                                <option value="keluar">Keluar</option>

                            </select>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Foto
                            </label>

                            <input
                                type="file"
                                class="form-control"
                                id="foto"
                                name="foto"
                                accept="image/*">

                        </div>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Alamat
                        </label>

                        <textarea
                            class="form-control"
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