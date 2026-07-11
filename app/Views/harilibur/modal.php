<div class="modal fade" id="modalHariLibur" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog">

        <form id="formHariLibur">

            <?= csrf_field() ?>

            <input type="hidden" id="id" name="id">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="modalTitle">
                        Tambah Hari Libur
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Tanggal
                        </label>

                        <input
                            type="date"
                            class="form-control"
                            id="tanggal"
                            name="tanggal"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Nama Hari Libur
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="nama_libur"
                            name="nama_libur"
                            maxlength="100"
                            autocomplete="off"
                            placeholder="Contoh : Hari Kemerdekaan RI"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Keterangan
                        </label>

                        <textarea
                            class="form-control"
                            id="keterangan"
                            name="keterangan"
                            rows="3"
                            placeholder="Keterangan (opsional)"></textarea>

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