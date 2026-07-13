<div class="modal fade" id="modalFoto">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5>

                    Ubah Foto Profil

                </h5>

                <button
                    class="btn-close"
                    data-bs-dismiss="modal"></button>

            </div>

            <form id="formFoto" enctype="multipart/form-data">

                <div class="modal-body">

                    <div class="text-center mb-3">

                        <img
                            id="previewUploadFoto"
                            src="<?= base_url('uploads/profile/' . ($profile['foto'] ?: 'default.png')) ?>"
                            width="170"
                            class="rounded-circle">

                    </div>

                    <input
                        type="file"
                        name="foto"
                        id="foto"
                        class="form-control"
                        accept="image/*">

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
                        id="btnUploadFoto">

                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<div class="modal fade" id="modalLogo">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5>

                    Ubah Logo Sekolah

                </h5>

                <button
                    class="btn-close"
                    data-bs-dismiss="modal"></button>

            </div>

            <form id="formLogo" enctype="multipart/form-data">

                <div class="modal-body">

                    <div class="text-center mb-3">

                        <img
                            id="previewUploadLogo"
                            src="<?= base_url('uploads/logo/' . ($profile['logo'] ?: 'logo.png')) ?>"
                            width="170">

                    </div>

                    <input
                        type="file"
                        name="logo"
                        id="logo"
                        class="form-control"
                        accept="image/*">

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
                        class="btn btn-success"
                        id="btnUploadLogo">

                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>