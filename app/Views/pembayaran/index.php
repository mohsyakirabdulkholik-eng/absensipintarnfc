<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="mb-4">

        <h2 class="mb-1">
            Support
        </h2>

        <p class="text-muted mb-0">
            Support atau dukungan untuk founder dan pengembang layanan Absensi Pintar berbasis NFC dalam menyediakan layanan absensi digital dengan memanfaatkan teknologi NFC yang ada di smartphone.
        </p>

    </div>


    <!-- Paket -->
    <div class="card mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Dukungan & terima kasih
            </h5>

        </div>


        <div class="card-body">

            <div class="row g-3">

                <?php foreach ($paket as $item): ?>

                    <div class="col-md-6 col-lg-4">

                        <div class="card h-100 border">

                            <div class="card-body">

                                <h4 class="mb-2">
                                    <?= esc(
                                        $item['nama_paket']
                                    ) ?>
                                </h4>


                                <div class="fs-4 fw-bold mb-2">

                                    <?= number_format( 
                                        (float) $item['harga'],
                                        0,
                                        ',',
                                        '.'
                                    ) ?>

                                </div>


                                <div class="text-muted mb-3">

                                    <?= (int) (
                                        $item['durasi_hari']
                                    ) ?>
                                    hari

                                </div>


                                <?php if (
                                    !empty(
                                        $item['deskripsi']
                                    )
                                ): ?>

                                    <p class="text-muted">

                                        <?= esc(
                                            $item['deskripsi']
                                        ) ?>

                                    </p>

                                <?php endif; ?>


                                <button
                                    type="button"
                                    class="btn btn-primary w-100"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalPembayaran"
                                    data-id-paket="<?= $item['id'] ?>"
                                    data-nama-paket="<?= esc(
                                        $item['nama_paket']
                                    ) ?>"
                                    data-harga="<?= number_format(
                                        (float) $item['harga'],
                                        0,
                                        ',',
                                        '.'
                                    ) ?>"
                                >
                                    Bayar Sekarang
                                </button>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        </div>

    </div>


    <!-- Riwayat -->
    <div class="card">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Riwayat Pembayaran
            </h5>

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>

                        <tr>

                            <th>No</th>
                            <th>Paket</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                            <th>Status</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php if (
                            empty($pembayaran)
                        ): ?>

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center py-4 text-muted"
                                >
                                    Belum ada riwayat pembayaran.
                                </td>

                            </tr>

                        <?php else: ?>

                            <?php foreach (
                                $pembayaran
                                as $i => $item
                            ): ?>

                                <tr>

                                    <td>
                                        <?= $i + 1 ?>
                                    </td>

                                    <td>
                                        <?= esc(
                                            $item['nama_paket']
                                            ?? '-'
                                        ) ?>
                                    </td>

                                    <td>

                                        Rp<?= number_format(
                                            (float) $item['jumlah'],
                                            0,
                                            ',',
                                            '.'
                                        ) ?>

                                    </td>

                                    <td>

                                        <?= !empty(
                                            $item[
                                                'tanggal_pembayaran'
                                            ]
                                        )
                                            ? date(
                                                'd-m-Y',
                                                strtotime(
                                                    $item[
                                                        'tanggal_pembayaran'
                                                    ]
                                                )
                                            )
                                            : '-'
                                        ?>

                                    </td>

                                    <td>

                                        <?php if (
                                            $item['status']
                                                === 'menunggu'
                                        ): ?>

                                            <span
                                                class="badge text-bg-warning"
                                            >
                                                Menunggu
                                            </span>

                                        <?php elseif (
                                            $item['status']
                                                === 'disetujui'
                                        ): ?>

                                            <span
                                                class="badge text-bg-success"
                                            >
                                                Disetujui
                                            </span>

                                        <?php else: ?>

                                            <span
                                                class="badge text-bg-danger"
                                            >
                                                Ditolak
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


<!-- Modal Pembayaran -->
<div
    class="modal fade"
    id="modalPembayaran"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                method="post"
                action="<?= base_url('pembayaran') ?>"
                enctype="multipart/form-data"
            >

                <?= csrf_field() ?>


                <div class="modal-header">

                    <h5 class="modal-title">
                        Pembayaran Langganan
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>


                <div class="modal-body">

                    <input
                        type="hidden"
                        name="id_paket"
                        id="idPaket"
                    >


                    <div class="mb-3">

                        <label class="form-label">
                            Paket
                        </label>

                        <input
                            type="text"
                            id="namaPaket"
                            class="form-control"
                            readonly
                        >

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Jumlah
                        </label>

                        <input
                            type="text"
                            id="hargaPaket"
                            class="form-control"
                            readonly
                        >

                    </div>


                    <div class="alert alert-info">

                        Silakan transfer sesuai nominal
                        pembayaran, kemudian upload
                        bukti transfer.

                    </div>


                    <div class="mb-3">

                        <label class="form-label">
                            Bukti Pembayaran
                            <span class="text-danger">
                                *
                            </span>
                        </label>

                        <input
                            type="file"
                            name="bukti_pembayaran"
                            class="form-control"
                            accept=".jpg,.jpeg,.png,.webp"
                            required
                        >

                        <small class="text-muted">
                            JPG, PNG, atau WEBP. Maksimal 5 MB.
                        </small>

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
                        class="btn btn-primary"
                    >
                        Kirim Pembayaran
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<script>

document
    .getElementById('modalPembayaran')
    .addEventListener(
        'show.bs.modal',
        function (event) {

            const button =
                event.relatedTarget;

            const idPaket =
                button.getAttribute(
                    'data-id-paket'
                );

            const namaPaket =
                button.getAttribute(
                    'data-nama-paket'
                );

            const harga =
                button.getAttribute(
                    'data-harga'
                );


            document.getElementById(
                'idPaket'
            ).value = idPaket;


            document.getElementById(
                'namaPaket'
            ).value = namaPaket;


            document.getElementById(
                'hargaPaket'
            ).value =
                'Rp' + harga;

        }
    );

</script>

<?= $this->endSection() ?>