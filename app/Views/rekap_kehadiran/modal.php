<!-- MODAL DETAIL REKAP -->
<div
    class="modal fade"
    id="modalDetailRekap"
    tabindex="-1"
    aria-labelledby="modalDetailRekapLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-xl modal-dialog-scrollable">

        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header">

                <div>
                    <h5
                        class="modal-title"
                        id="modalDetailRekapLabel"
                    >
                        Detail Kehadiran
                    </h5>

                    <small
                        class="text-muted"
                        id="detailPeriode"
                    ></small>
                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>


            <!-- BODY -->
            <div class="modal-body">

                <!-- IDENTITAS -->
                <div class="card bg-light border-0 mb-3">

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-4">

                                <small class="text-muted">
                                    Nama
                                </small>

                                <div
                                    class="fw-semibold"
                                    id="detailNama"
                                >
                                    -
                                </div>

                            </div>


                            <div class="col-md-4">

                                <small
                                    class="text-muted"
                                    id="detailLabelNomor"
                                >
                                    NIS
                                </small>

                                <div
                                    class="fw-semibold"
                                    id="detailNomor"
                                >
                                    -
                                </div>

                            </div>


                            <div
                                class="col-md-4"
                                id="detailKelasContainer"
                            >

                                <small
                                    class="text-muted"
                                    id="detailLabelTambahan"
                                >
                                    Kelas
                                </small>

                                <div
                                    class="fw-semibold"
                                    id="detailTambahan"
                                >
                                    -
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- TABLE DETAIL -->
                <div class="table-responsive">

                    <table
                        class="table table-bordered table-striped align-middle"
                        id="tableDetailRekap"
                    >

                        <thead class="table-light">

                            <tr>
                                <th width="5%">No</th>
                                <th>Hari</th>
                                <th>Tanggal</th>
                                <th>Jam Masuk</th>
                                <th>Jam Pulang</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>

                        </thead>

                        <tbody id="detailRekapBody">

                            <tr>
                                <td
                                    colspan="7"
                                    class="text-center text-muted"
                                >
                                    Belum ada data.
                                </td>
                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>


            <!-- FOOTER -->
            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Tutup
                </button>

            </div>

        </div>

    </div>
</div>