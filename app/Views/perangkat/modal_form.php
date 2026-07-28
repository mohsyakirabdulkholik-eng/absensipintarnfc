<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="modalFormLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formPerangkat">

            <?= csrf_field() ?>

            <input type="hidden" id="id" name="id">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="modalFormLabel">
                        Tambah Perangkat
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
                            Nama Perangkat
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="nama_perangkat"
                            name="nama_perangkat"
                            placeholder="Contoh: Mesin Absensi Utama"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Jenis Perangkat
                        </label>

                        <select
                            class="form-select"
                            id="jenis_perangkat"
                            name="jenis_perangkat"
                            required>

                            <option value="android">
                                Android
                            </option>

                            <option value="nodemcu">
                                NodeMCU
                            </option>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            class="form-select"
                            id="status"
                            name="status">

                            <option value="aktif">
                                Aktif
                            </option>

                            <option value="nonaktif">
                                Nonaktif
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