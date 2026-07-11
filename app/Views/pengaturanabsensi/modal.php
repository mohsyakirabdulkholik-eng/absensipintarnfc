<div class="modal fade" id="modalPengaturan" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <form id="formPengaturan">

            <?= csrf_field() ?>

            <input type="hidden" id="id" name="id">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="modalTitle">
                        Tambah Pengaturan Absensi
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Jam Masuk
                            </label>

                            <input
                                type="time"
                                class="form-control"
                                id="jam_masuk"
                                name="jam_masuk"
                                required>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Batas Terlambat
                            </label>

                            <input
                                type="time"
                                class="form-control"
                                id="batas_terlambat"
                                name="batas_terlambat"
                                required>

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Jam Pulang
                            </label>

                            <input
                                type="time"
                                class="form-control"
                                id="jam_pulang"
                                name="jam_pulang"
                                required>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Toleransi Pulang
                            </label>

                            <input
                                type="time"
                                class="form-control"
                                id="toleransi_pulang"
                                name="toleransi_pulang"
                                required>

                        </div>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Keterangan
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="keterangan"
                            name="keterangan"
                            maxlength="100"
                            placeholder="Contoh : Jam Normal"
                            autocomplete="off">

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                        id="btnSimpan">

                        Simpan

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>