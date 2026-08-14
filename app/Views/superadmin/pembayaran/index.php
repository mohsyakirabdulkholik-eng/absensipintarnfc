<?= $this->extend('layouts/superadmin') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <!-- Header -->
    <div class="mb-4">

        <h2 class="mb-1">
            Pembayaran
        </h2>

        <p class="text-muted mb-0">
            Kelola dan pantau pembayaran langganan seluruh sekolah.
        </p>

    </div>


    <!-- Card -->
    <div class="card">

        <div class="card-header bg-white">

            <h5 class="mb-0">
                Daftar Pembayaran
            </h5>

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th width="60">
                                No
                            </th>

                            <th>
                                Sekolah
                            </th>

                            <th>
                                Paket
                            </th>

                            <th>
                                Jumlah
                            </th>

                            <th>
                                Tanggal
                            </th>

                            <th>
                                Metode
                            </th>

                            <th>
                                Status
                            </th>

                            <th width="100">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php if (empty($pembayaran)): ?>

                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center py-5 text-muted"
                                >

                                    Belum ada pembayaran.

                                </td>

                            </tr>

                        <?php else: ?>

                            <?php foreach (
                                $pembayaran
                                as $i => $item
                            ): ?>

                                <tr>

                                    <!-- No -->
                                    <td>
                                        <?= $i + 1 ?>
                                    </td>


                                    <!-- Sekolah -->
                                    <td>

                                        <strong>
                                            <?= esc(
                                                $item['nama_sekolah']
                                            ) ?>
                                        </strong>

                                    </td>


                                    <!-- Paket -->
                                    <td>

                                        <?= esc(
                                            $item['nama_paket']
                                        ) ?>

                                        <small class="text-muted d-block">

                                            <?= (int) (
                                                $item['durasi_hari']
                                                ?? 0
                                            ) ?>
                                            hari

                                        </small>

                                    </td>


                                    <!-- Jumlah -->
                                    <td>

                                        <strong>
                                            Rp<?= number_format(
                                                (float) $item['jumlah'],
                                                0,
                                                ',',
                                                '.'
                                            ) ?>
                                        </strong>

                                    </td>


                                    <!-- Tanggal -->
                                    <td>

                                        <?= !empty(
                                            $item['tanggal_pembayaran']
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


                                    <!-- Metode -->
                                    <td>

                                        <?= esc(
                                            ucfirst(
                                                $item[
                                                    'metode_pembayaran'
                                                ] ?? '-'
                                            )
                                        ) ?>

                                    </td>


                                    <!-- Status -->
                                    <td>

                                        <?php
                                        $status =
                                            $item['status']
                                            ?? 'menunggu';
                                        ?>


                                        <?php if (
                                            $status === 'menunggu'
                                        ): ?>

                                            <span
                                                class="badge text-bg-warning"
                                            >
                                                Menunggu
                                            </span>

                                        <?php elseif (
                                            $status === 'disetujui'
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


                                    <!-- Aksi -->
                                    <td>

                                        <a
                                            href="<?= base_url(
                                                'superadmin/pembayaran/detail/'
                                                . $item['id']
                                            ) ?>"
                                            class="btn btn-sm btn-primary"
                                        >
                                            Detail
                                        </a>

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

<?= $this->endSection() ?>