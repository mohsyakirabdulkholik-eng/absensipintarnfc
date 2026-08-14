<?= $this->extend('layouts/superadmin') ?>

<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="mb-4">

        <h2 class="mb-1">
            Langganan
        </h2>

        <p class="text-muted mb-0">
            Kelola dan pantau langganan seluruh sekolah.
        </p>

    </div>


    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                <h5 class="mb-0">
                    Daftar Langganan
                </h5>

                <div class="btn-group" role="group">

                    <button
                        type="button"
                        class="btn btn-sm btn-primary filter-status active"
                        data-filter="semua"
                    >
                        Semua
                    </button>

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-primary filter-status"
                        data-filter="aktif"
                    >
                        Aktif
                    </button>

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-warning filter-status"
                        data-filter="akan-berakhir"
                    >
                        Akan Berakhir
                    </button>

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-danger filter-status"
                        data-filter="expired"
                    >
                        Expired
                    </button>

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-secondary filter-status"
                        data-filter="belum"
                    >
                        Belum Berlangganan
                    </button>

                </div>

            </div>

        </div>


        <div class="card-body">

            <?php if (empty($langganan)): ?>

                <div class="text-center py-5">

                    <i class="bi bi-credit-card fs-1 text-muted"></i>

                    <h5 class="mt-3">
                        Belum ada langganan
                    </h5>

                    <p class="text-muted mb-0">
                        Belum ada sekolah yang memiliki langganan.
                    </p>

                </div>

            <?php else: ?>

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead>

                            <tr >

                                <th>No</th>

                                <th>Sekolah</th>

                                <th>Paket</th>

                                <th>Mulai</th>

                                <th>Berakhir</th>

                                <th>Sisa</th>

                                <th>Status</th>

                                <th>Aksi</th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php foreach ($langganan as $i => $item): ?>

                               <?php

                                    $hasSubscription = !empty($item['id']);

                                    $sisaHari = 0;
                                    $aktif = false;
                                    $akanBerakhir = false;

                                    if ($hasSubscription && !empty($item['tanggal_berakhir'])) {

                                        $today = new DateTime(date('Y-m-d'));

                                        $end = new DateTime(
                                            $item['tanggal_berakhir']
                                        );

                                        if ($end >= $today) {

                                            $sisaHari = (int) $today
                                                ->diff($end)
                                                ->days;
                                        }

                                        $aktif =
                                            $item['status'] === 'aktif'
                                            &&
                                            $item['tanggal_berakhir'] >= date('Y-m-d');

                                        $akanBerakhir =
                                            $aktif
                                            &&
                                            $sisaHari <= 7;
                                    }

                                    ?>

                                <tr class="row-langganan"
                                data-status="<?= $hasSubscription
                                    ? ($akanBerakhir
                                        ? 'akan-berakhir'
                                        : ($aktif ? 'aktif' : 'expired'))
                                    : 'belum'
                                ?>">

                                    <td>
                                        <?= $i + 1 ?>
                                    </td>


                                    <td>

                                        <strong>
                                            <?= esc($item['nama_sekolah']) ?>
                                        </strong>

                                    </td>


                                    <td>

                                        <?php if ($hasSubscription): ?>

                                            <?= esc(
                                                $item['nama_paket'] ?? '-'
                                            ) ?>

                                            <?php if (isset($item['harga'])): ?>

                                                <small class="text-muted d-block">
                                                    Rp<?= number_format(
                                                        (float) $item['harga'],
                                                        0,
                                                        ',',
                                                        '.'
                                                    ) ?>
                                                </small>

                                            <?php endif; ?>

                                        <?php else: ?>

                                            <span class="text-muted">
                                                -
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <td>

                                        <?php if (
                                            $hasSubscription &&
                                            !empty($item['tanggal_mulai'])
                                        ): ?>

                                            <?= date(
                                                'd-m-Y',
                                                strtotime($item['tanggal_mulai'])
                                            ) ?>

                                        <?php else: ?>

                                            <span class="text-muted">
                                                -
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <td>

                                        <?php if (
                                            $hasSubscription &&
                                            !empty($item['tanggal_berakhir'])
                                        ): ?>

                                            <?= date(
                                                'd-m-Y',
                                                strtotime($item['tanggal_berakhir'])
                                            ) ?>

                                        <?php else: ?>

                                            <span class="text-muted">
                                                -
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <td>

                                        <?php if ($aktif): ?>

                                            <strong>
                                                <?= $sisaHari ?>
                                            </strong>
                                            hari

                                        <?php else: ?>

                                            <span class="text-muted">
                                                -
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <td>

                                        <?php if (!$hasSubscription): ?>

                                            <span class="badge text-bg-secondary">
                                                Belum Berlangganan
                                            </span>

                                        <?php elseif ($akanBerakhir): ?>

                                            <span class="badge text-bg-warning">
                                                Akan Berakhir
                                            </span>

                                        <?php elseif ($aktif): ?>

                                            <span class="badge text-bg-success">
                                                Aktif
                                            </span>

                                        <?php else: ?>

                                            <span class="badge text-bg-danger">
                                                Expired
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <td>

                                        <a
                                            href="<?= base_url(
                                                'superadmin/sekolah/detail/'
                                                . $item['id_sekolah']
                                            ) ?>"
                                            class="btn btn-sm btn-primary"
                                        >
                                            Detail
                                        </a>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>

<?= $this->endSection() ?>