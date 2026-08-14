<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="mb-1">
                Detail Pembayaran
            </h2>

            <p class="text-muted mb-0">
                Informasi lengkap pembayaran langganan.
            </p>
        </div>

        <a
            href="<?= base_url('superadmin/pembayaran') ?>"
            class="btn btn-outline-secondary"
        >
            Kembali
        </a>

    </div>


    <!-- Informasi Pembayaran -->
    <div class="card mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Informasi Pembayaran
            </h5>

        </div>


        <div class="card-body">

            <div class="row g-4">

                <!-- Sekolah -->
                <div class="col-md-6">

                    <div class="text-muted small">
                        Sekolah
                    </div>

                    <div class="fw-semibold">
                        <?= esc(
                            $pembayaran['nama_sekolah']
                        ) ?>
                    </div>

                </div>


                <!-- Paket -->
                <div class="col-md-6">

                    <div class="text-muted small">
                        Paket
                    </div>

                    <div class="fw-semibold">
                        <?= esc(
                            $pembayaran['nama_paket']
                        ) ?>
                    </div>

                </div>


                <!-- Jumlah -->
                <div class="col-md-6">

                    <div class="text-muted small">
                        Jumlah Pembayaran
                    </div>

                    <div class="fw-semibold fs-5">

                        Rp<?= number_format(
                            (float) $pembayaran['jumlah'],
                            0,
                            ',',
                            '.'
                        ) ?>

                    </div>

                </div>


                <!-- Durasi -->
                <div class="col-md-6">

                    <div class="text-muted small">
                        Durasi
                    </div>

                    <div class="fw-semibold">

                        <?= (int) (
                            $pembayaran['durasi_hari']
                            ?? 0
                        ) ?>
                        hari

                    </div>

                </div>


                <!-- Tanggal -->
                <div class="col-md-6">

                    <div class="text-muted small">
                        Tanggal Pembayaran
                    </div>

                    <div class="fw-semibold">

                        <?= !empty(
                            $pembayaran['tanggal_pembayaran']
                        )
                            ? date(
                                'd-m-Y',
                                strtotime(
                                    $pembayaran[
                                        'tanggal_pembayaran'
                                    ]
                                )
                            )
                            : '-'
                        ?>

                    </div>

                </div>


                <!-- Metode -->
                <div class="col-md-6">

                    <div class="text-muted small">
                        Metode Pembayaran
                    </div>

                    <div class="fw-semibold">

                        <?= esc(
                            ucfirst(
                                $pembayaran[
                                    'metode_pembayaran'
                                ] ?? '-'
                            )
                        ) ?>

                    </div>

                </div>


                <!-- Status -->
                <div class="col-md-6">

                    <div class="text-muted small mb-1">
                        Status
                    </div>

                    <?php
                    $status =
                        $pembayaran['status']
                        ?? 'menunggu';
                    ?>

                    <?php if (
                        $status === 'menunggu'
                    ): ?>

                        <span class="badge text-bg-warning">
                            Menunggu
                        </span>

                    <?php elseif (
                        $status === 'disetujui'
                    ): ?>

                        <span class="badge text-bg-success">
                            Disetujui
                        </span>

                    <?php else: ?>

                        <span class="badge text-bg-danger">
                            Ditolak
                        </span>

                    <?php endif; ?>

                </div>


                <!-- Catatan -->
                <div class="col-md-6">

                    <div class="text-muted small">
                        Catatan
                    </div>

                    <div>

                        <?= !empty(
                            $pembayaran['catatan']
                        )
                            ? nl2br(
                                esc(
                                    $pembayaran['catatan']
                                )
                            )
                            : '<span class="text-muted">-</span>'
                        ?>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- Bukti Pembayaran -->
    <?php if (
        !empty($pembayaran['bukti_pembayaran'])
    ): ?>

        <div class="card mb-4">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Bukti Pembayaran
                </h5>

            </div>

            <div class="card-body text-center">

                <img
                    src="<?= base_url(
                        'uploads/pembayaran/'
                        . $pembayaran[
                            'bukti_pembayaran'
                        ]
                    ) ?>"
                    alt="Bukti Pembayaran"
                    class="img-fluid rounded"
                    style="max-height:500px;"
                >

            </div>

        </div>

    <?php endif; ?>


    <!-- Aksi -->
    <?php if ($status === 'menunggu'): ?>

        <div class="card">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Proses Pembayaran
                </h5>

            </div>


            <div class="card-body">

                <div class="d-flex flex-wrap gap-2">

                    <!-- Setujui -->
                    <form
                        method="post"
                        action="<?= base_url(
                            'superadmin/pembayaran/approve/'
                            . $pembayaran['id']
                        ) ?>"
                        onsubmit="return confirm(
                            'Setujui pembayaran ini?'
                        );"
                    >

                        <?= csrf_field() ?>

                        <button
                            type="submit"
                            class="btn btn-success"
                        >
                            <i class="fa-solid fa-check me-1"></i>
                            Setujui Pembayaran
                        </button>

                    </form>


                    <!-- Tolak -->
                    <button
                        type="button"
                        class="btn btn-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#modalTolak"
                    >
                        <i class="fa-solid fa-xmark me-1"></i>
                        Tolak Pembayaran
                    </button>

                </div>

            </div>

        </div>

    <?php endif; ?>


</div>


<!-- Modal Tolak -->
<div
    class="modal fade"
    id="modalTolak"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                method="post"
                action="<?= base_url(
                    'superadmin/pembayaran/reject/'
                    . $pembayaran['id']
                ) ?>"
            >

                <?= csrf_field() ?>

                <div class="modal-header">

                    <h5 class="modal-title">
                        Tolak Pembayaran
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>


                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Alasan Penolakan
                        </label>

                        <textarea
                            name="catatan"
                            class="form-control"
                            rows="4"
                            placeholder="Masukkan alasan penolakan..."
                            required
                        ></textarea>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="btn btn-danger"
                    >
                        Tolak Pembayaran
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<?= $this->endSection() ?>