<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="card">

    <div class="card-header">

        <h3 class="card-title">

            <i class="fa-solid fa-school me-2"></i>

            Pengaturan Sekolah

        </h3>

        <small class="text-muted">

            Perubahan pada halaman ini akan digunakan pada Dashboard, Laporan dan Aplikasi Android.

        </small>

    </div>

    <form id="formSekolah">

        <?= csrf_field() ?>

        <div class="card-body">

            <div class="row">

                <!-- Informasi Sekolah -->

                <div class="col-lg-6">

                    <div class="card card-outline card-primary">

                        <div class="card-header">

                            <h5 class="mb-0">

                                Informasi Sekolah

                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="mb-3">

                                <label class="form-label">

                                    Nama Sekolah

                                </label>

                                <input
                                    type="text"
                                    id="nama_sekolah"
                                    name="nama_sekolah"
                                    class="form-control"
                                    value="<?= esc($sekolah['nama_sekolah']) ?>">

                            </div>

                            <div class="mb-3">

                                <label class="form-label">

                                    Jenjang

                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    value="<?= esc($sekolah['nama_jenjang']) ?>"
                                    readonly>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">

                                    NPSN

                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    value="<?= esc($sekolah['npsn']) ?>"
                                    readonly>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">

                                    Alamat

                                </label>

                                <textarea
                                    id="alamat"
                                    name="alamat"
                                    rows="4"
                                    class="form-control"><?= esc($sekolah['alamat']) ?></textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Kontak -->

                <div class="col-lg-6">

                    <div class="card card-outline card-success">

                        <div class="card-header">

                            <h5 class="mb-0">

                                Kontak Sekolah

                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="mb-3">

                                <label class="form-label">

                                    Telepon

                                </label>

                                <input
                                    type="text"
                                    id="telepon"
                                    name="telepon"
                                    class="form-control"
                                    value="<?= esc($sekolah['telepon']) ?>">

                            </div>

                            <div class="mb-3">

                                <label class="form-label">

                                    Email

                                </label>

                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    class="form-control"
                                    value="<?= esc($sekolah['email']) ?>">

                            </div>

                            <div class="mb-3">

                                <label class="form-label">

                                    Website

                                </label>

                                <input
                                    type="url"
                                    id="website"
                                    name="website"
                                    class="form-control"
                                    placeholder="https://"
                                    value="<?= esc($sekolah['website']) ?>">

                            </div>

                            <div class="mb-3">

                                <label class="form-label">

                                    Kepala Sekolah

                                </label>

                                <input
                                    type="text"
                                    id="kepala_sekolah"
                                    name="kepala_sekolah"
                                    class="form-control"
                                    value="<?= esc($sekolah['kepala_sekolah']) ?>">

                            </div>

                            <div class="mb-3">

                                <label class="form-label">

                                    Motto Sekolah

                                </label>

                                <textarea
                                    id="motto"
                                    name="motto"
                                    rows="3"
                                    class="form-control"><?= esc($sekolah['motto']) ?></textarea>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="card-footer text-end">

            <button
                type="submit"
                id="btnSimpan"
                class="btn btn-primary">

                <i class="fa-solid fa-floppy-disk me-2"></i>

                Simpan Perubahan

            </button>

        </div>

    </form>

</div>

<script>

    const BASE_URL = "<?= base_url() ?>/";

</script>


<?= $this->endSection() ?>