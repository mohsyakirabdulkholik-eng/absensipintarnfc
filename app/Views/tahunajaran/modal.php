<div class="modal fade" id="modalTahunAjaran" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog">

        <form id="formTahunAjaran">

            <?= csrf_field() ?>

            <input type="hidden" id="id" name="id">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="modalTitle">
                        Tambah Tahun Ajaran
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
                            Tahun Ajaran
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="nama"
                            name="nama"
                            placeholder="Contoh : 2026/2027"
                            maxlength="9"
                            autocomplete="off"
                            required>

                        <small class="text-muted">
                            Format: 2026/2027
                        </small>

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