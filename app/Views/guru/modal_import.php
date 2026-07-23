<div class="modal fade" id="modalImport" tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-scrollable">

        <div class="modal-content">

            <form id="formImport">

                <div class="modal-header">

                    <h5 class="modal-title">

                        <i class="fas fa-file-excel me-2"></i>
                        Import Data Guru

                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <!-- UPLOAD -->
                    <div id="stepUpload">

                        <label class="form-label">
                            Pilih File Excel
                        </label>

                        <input
                            type="file"
                            class="form-control"
                            id="file_excel"
                            name="file_excel"
                            accept=".xlsx,.xls"
                            required>

                        <div class="form-text">
                            Format file Excel (.xlsx)
                        </div>

                    </div>


                    <!-- PREVIEW -->
                    <div id="stepPreview" class="d-none">

                        <div class="row g-2 mb-3">

                            <div class="col-md-4">

                                <div class="border rounded p-3 text-center">

                                    <small class="text-muted">
                                        Total Data
                                    </small>

                                    <h4
                                        class="mb-0"
                                        id="previewTotal">
                                        0
                                    </h4>

                                </div>

                            </div>

                            <div class="col-md-4">

                                <div class="border rounded p-3 text-center">

                                    <small class="text-muted">
                                        Valid
                                    </small>

                                    <h4
                                        class="mb-0 text-success"
                                        id="previewValid">
                                        0
                                    </h4>

                                </div>

                            </div>

                            <div class="col-md-4">

                                <div class="border rounded p-3 text-center">

                                    <small class="text-muted">
                                        Bermasalah
                                    </small>

                                    <h4
                                        class="mb-0 text-danger"
                                        id="previewInvalid">
                                        0
                                    </h4>

                                </div>

                            </div>

                        </div>


                        <div class="table-responsive">

                            <table
                                class="table table-bordered table-sm align-middle">

                                <thead class="table-light">

                                    <tr>
                                        <th>Baris</th>
                                        <th>NIP</th>
                                        <th>Nama</th>
                                        <th>Jabatan</th>
                                        <th>Status</th>
                                    </tr>

                                </thead>

                                <tbody id="previewBody"></tbody>

                            </table>

                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        id="btnKembali"
                        style="display:none;">

                        <i class="fas fa-arrow-left me-1"></i>
                        Kembali

                    </button>

                    <button
                        type="button"
                        class="btn btn-secondary"
                        id="btnBatal"
                        data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                        id="btnPreview">

                        <i class="fas fa-eye me-1"></i>
                        Preview

                    </button>

                    <button
                        type="button"
                        class="btn btn-success"
                        id="btnImportSekarang"
                        style="display:none;">

                        <i class="fas fa-file-import me-1"></i>
                        Import Sekarang

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>