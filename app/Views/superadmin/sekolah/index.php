<?= $this->extend('layouts/superadmin') ?>

<?= $this->section('content') ?>

<div class="container-fluid superadmin-sekolah">

    <div class="d-flex justify-content-between align-items-center mb-3 superadmin-sekolah-header">

        <div>
            <h3 class="mb-1">
                Daftar Sekolah
            </h3>

            <p class="text-muted mb-0">
                Daftar seluruh sekolah yang terdaftar di platform.
            </p>
        </div>

    </div>


    <div class="card shadow-sm border-0 sekolah-card">

        <div class="card-body">

            <div class="table-responsive">

                <table
                    id="tableSekolah"
                    class="table table-hover align-middle w-100"
                >

                    <thead>

                        <tr>
                            <th>No</th>
                            <th>Sekolah</th>
                            <th>Jenjang</th>
                            <th>NPSN</th>
                            <th>Admin</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($sekolah as $index => $item): ?>

                            <tr>

                                <td>
                                    <?= $index + 1 ?>
                                </td>

                                <td>
                                    <div class="fw-semibold">
                                        <?= esc($item['nama_sekolah']) ?>
                                    </div>

                                    <small class="text-muted">
                                        <?= esc($item['email'] ?? '-') ?>
                                    </small>
                                </td>

                                <td>
                                    <?= esc($item['nama_jenjang'] ?? '-') ?>
                                </td>

                                <td>
                                    <?= esc($item['npsn'] ?? '-') ?>
                                </td>

                                <td>

                                    <?php if (!empty($item['nama_admin'])): ?>

                                        <div>
                                            <?= esc($item['nama_admin']) ?>
                                        </div>

                                        <small class="text-muted">
                                            @<?= esc($item['username_admin']) ?>
                                        </small>

                                    <?php else: ?>

                                        <span class="text-muted">
                                            Belum ada admin
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <?php if ($item['status'] === 'aktif'): ?>

                                        <span class="badge text-bg-success">
                                            Aktif
                                        </span>

                                    <?php else: ?>

                                        <span class="badge text-bg-secondary">
                                            <?= esc(ucfirst($item['status'])) ?>
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <a
                                        href="<?= base_url(
                                            'superadmin/sekolah/detail/' . $item['id']
                                        ) ?>"
                                        class="btn btn-sm btn-primary"
                                    >
                                        <i class="fas fa-eye"></i> Detail
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<?= $this->endSection() ?>