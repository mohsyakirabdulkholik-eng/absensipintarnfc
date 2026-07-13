<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="row">

    <!-- PROFIL ADMIN -->

    <div class="col-lg-5">

        <div class="card">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fa-solid fa-user me-2"></i>

                    Profil Administrator

                </h5>

            </div>

            <div class="card-body">
                 <div class="text-center mb-4">

                    <div class="position-relative d-inline-block">

                        <?php if (!empty($profile['foto']) && $profile['foto'] != 'default.png'): ?>

                            <img
                                id="previewFoto"
                                src="<?= base_url('uploads/profile/' . $profile['foto']) ?>"
                                class="rounded-circle border shadow"
                                width="150"
                                height="150"
                                style="object-fit:cover;">

                        <?php else: ?>

                            <div
                                id="previewFoto"
                                class="rounded-circle bg-light border shadow d-flex justify-content-center align-items-center"
                                style="width:150px;height:150px;">

                                <i class="fa-solid fa-user fa-4x text-secondary"></i>

                            </div>

                        <?php endif; ?>

                        <!-- Tombol Kamera -->

                        <button
                            type="button"
                            class="btn btn-primary rounded-circle position-absolute"
                            id="btnFoto"
                            style="
                                width:42px;
                                height:42px;
                                bottom:5px;
                                right:5px;">

                            <i class="fa-solid fa-camera"></i>

                        </button>

                    </div>

                </div>

                    <div class="mb-3">

                        <label>Foto Profil</label>

                    </div>

                    <div class="mb-3">

                        <label>Nama Lengkap</label>

                        <input
                            type="text"
                            name="nama"
                            class="form-control"
                            value="<?= esc($profile['nama']) ?>">

                    </div>

                    <div class="mb-3">

                        <label>Username</label>

                        <input
                            class="form-control"
                            value="<?= esc($profile['username']) ?>"
                            readonly>

                    </div>

                    <div class="mb-3">

                        <label>Email</label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="<?= esc($profile['email']) ?>">

                    </div>

                    <div class="mb-3">

                        <label>No HP</label>

                        <input
                            type="text"
                            name="no_hp"
                            class="form-control"
                            value="<?= esc($profile['no_hp']) ?>">

                    </div>

                    <div class="mb-4">

                        <label>Role</label>

                        <input
                            class="form-control"
                            value="<?= esc($profile['nama_role']) ?>"
                            readonly>

                    </div>

                    <button
                        id="btnSimpan"
                        class="btn btn-primary w-100">

                        <i class="fa-solid fa-floppy-disk me-2"></i>

                        Simpan Perubahan

                    </button>

                </form>

            </div>

        </div>

    </div>

    <!-- DATA SEKOLAH -->

    <div class="col-lg-7">

        <div class="card">

            <div class="card-header">

                <h5 class="mb-0">

                    <i class="fa-solid fa-school me-2"></i>

                    Informasi Sekolah

                </h5>

            </div>

            <div class="card-body">

                <div class="text-center mb-4">

                    <div class="position-relative d-inline-block">

                        <?php if (!empty($profile['logo']) && $profile['logo'] != 'default-school.png'): ?>

                            <img
                                id="previewLogo"
                                src="<?= base_url('uploads/logo/' . $profile['logo']) ?>"
                                width="150">

                        <?php else: ?>

                            <div
                                id="previewLogo"
                                class="border rounded shadow bg-light d-flex justify-content-center align-items-center"
                                style="width:150px;height:150px;">

                                <i class="fa-solid fa-school fa-4x text-secondary"></i>

                            </div>

                        <?php endif; ?>

                        <button
                            type="button"
                            id="btnLogo"
                            class="btn btn-success rounded-circle position-absolute"
                            style="
                                width:42px;
                                height:42px;
                                bottom:5px;
                                right:5px;">

                            <i class="fa-solid fa-camera"></i>

                        </button>

                    </div>

                </div>

                <table class="table table-bordered">

                    <tr>
                        <th width="35%">Nama Sekolah</th>
                        <td><?= esc($profile['nama_sekolah']) ?></td>
                    </tr>

                    <tr>
                        <th>Jenjang</th>
                        <td><?= esc($profile['nama_jenjang']) ?></td>
                    </tr>

                    <tr>
                        <th>NPSN</th>
                        <td><?= esc($profile['npsn']) ?></td>
                    </tr>

                    <tr>
                        <th>Telepon</th>
                        <td><?= esc($profile['telepon']) ?></td>
                    </tr>

                    <tr>
                        <th>Email</th>
                        <td><?= esc($profile['email_sekolah']) ?></td>
                    </tr>

                    <tr>
                        <th>Alamat</th>
                        <td><?= esc($profile['alamat']) ?></td>
                    </tr>

                </table>

                <div class="alert alert-info mb-0">

                    <i class="fa-solid fa-circle-info me-2"></i>

                    Informasi sekolah dikelola melalui menu
                    <strong>Pengaturan Sekolah</strong>.

                </div>

            </div>

        </div>

    </div>

</div>
<?= view('profile/modal') ?>
<script>
    const BASE_URL = "<?= base_url() ?>";
</script>

<?= $this->endSection() ?>