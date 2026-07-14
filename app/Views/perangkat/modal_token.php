<div class="modal fade" id="modalToken" tabindex="-1" aria-labelledby="modalTokenLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
        <input type="hidden" id="pairingId">
            <div class="modal-header">

                <div class="mb-3">

                    <h5
                        class="fw-bold"
                        id="pairingNamaPerangkat">

                    </h5>

                </div>

                <div class="mb-3">

                    <label class="form-label fw-bold">

                        Status Pairing

                    </label>

                    <div id="pairingStatus"></div>

                    <small
                        class="text-muted"
                        id="pairingDeviceInfo">

                    </small>

                </div>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

               <div class="text-center mb-3">

                    <h5 id="pairingNamaPerangkat" class="fw-bold mb-2"></h5>

                    <div id="pairingStatus" class="mb-2"></div>

                    <small id="pairingDeviceInfo" class="text-muted d-block"></small>

                </div>

                <hr>

                <div class="text-center mb-3">

                    <div id="qrcode" class="d-flex justify-content-center align-items-center mb-3"></div>

                </div>

                <hr>

                <div class="mb-3">

                    <label class="form-label fw-bold">
                        Token Pairing
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="tokenPerangkat"
                        readonly>

                </div>

                <div class="alert alert-info">

                    <i class="fas fa-mobile-alt me-2"></i>

                    Scan QR Code menggunakan aplikasi
                    <strong>Absensi Pintar NFC</strong>.

                </div>

            </div>

            <div class="modal-footer justify-content-between">

                <button
                    type="button"
                    class="btn btn-secondary"
                    id="btnCopyToken">

                    <i class="fas fa-copy me-1"></i>

                    Copy Token

                </button>

                <div>

                    <button
                        type="button"
                        class="btn btn-warning"
                        id="btnRegenerateToken">

                        <i class="fas fa-sync-alt me-1"></i>

                        Regenerate

                    </button>

                    <button
                        class="btn btn-danger"
                        data-bs-dismiss="modal">

                        Tutup

                    </button>

                </div>

            </div>
        </div>
    </div>
</div>