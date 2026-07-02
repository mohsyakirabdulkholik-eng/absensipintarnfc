<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="row">

    <?= view('components/dashboard_card',[
        'title'=>'Guru',
        'total'=>$guru,
        'icon'=>'fa-solid fa-user-tie',
        'color'=>'primary'
    ]) ?>

    <?= view('components/dashboard_card',[
        'title'=>'Siswa',
        'total'=>$siswa,
        'icon'=>'fa-solid fa-user-graduate',
        'color'=>'success'
    ]) ?>

    <?= view('components/dashboard_card',[
        'title'=>'Absensi Hari Ini',
        'total'=>$absensi,
        'icon'=>'fa-solid fa-calendar-check',
        'color'=>'warning'
    ]) ?>

    <?= view('components/dashboard_card',[
        'title'=>'Kartu NFC',
        'total'=>$nfc,
        'icon'=>'fa-solid fa-id-card',
        'color'=>'danger'
    ]) ?>

</div>

<div class="row mt-4">

    <div class="col-lg-8">

        <div class="card">

            <div class="card-header">

                <h3 class="card-title">

                    Grafik Kehadiran

                </h3>

            </div>

            <div class="card-body">

                <canvas id="attendanceChart"></canvas>

            </div>

        </div>

    </div>

    <div class="col-lg-4">

        <div class="card">

            <div class="card-header">

                <h3 class="card-title">

                    Aktivitas Terbaru

                </h3>

            </div>

            <div class="card-body">

                <ul class="list-group">

                    <li class="list-group-item">
                        Belum ada aktivitas
                    </li>
                  
                </ul>

            </div>

        </div>

    </div>

</div>

<?= $this->endSection() ?>