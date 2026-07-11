<div class="modal fade" id="modalSemester" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog">

        <form id="formSemester">

            <?= csrf_field() ?>

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="modalTitle">
                        Tambah Semester
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <input type="hidden" id="id">

                    <div class="mb-3">

                        <label class="form-label">
                            Tahun Ajaran
                        </label>

                        <select
                            class="form-select"
                            id="id_tahun"
                            name="id_tahun"
                            required>

                            <option value="">-- Pilih Tahun Ajaran --</option>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Semester
                        </label>

                        <select
                            class="form-select"
                            id="nama"
                            name="nama"
                            required>

                            <option value="">-- Pilih Semester --</option>

                            <option value="Ganjil">
                                Ganjil
                            </option>

                            <option value="Genap">
                                Genap
                            </option>

                        </select>

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