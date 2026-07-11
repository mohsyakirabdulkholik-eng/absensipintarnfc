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

                <div style="height:350px">
                    <canvas id="attendanceChart"></canvas>
                </div>

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

                    <?php if (!empty($aktivitas)) : ?>

                        <?php foreach ($aktivitas as $row) : ?>

                            <?php
                            $nama = $row['jenis'] === 'guru'
                                ? $row['nama']
                                : $row['nama_siswa'];

                            $jam = $row['jam_masuk'] ?: $row['jam_pulang'];
                            ?>

                            <li class="list-group-item">

                                <strong>
                                    <?= esc($nama) ?>
                                </strong>

                                <br>

                                <small class="text-muted">

                                    <?= ucfirst($row['jenis']) ?>

                                    •

                                    <?= esc($row['status']) ?>

                                    •

                                    <?= $jam ?>

                                </small>

                            </li>

                        <?php endforeach ?>

                    <?php else : ?>

                        <li class="list-group-item">

                            Belum ada aktivitas

                        </li>

                    <?php endif; ?>
                  
                </ul>

            </div>

        </div>

        <div class="card mt-3">

    <div class="card-header">

        <h3 class="card-title">

            Ringkasan Hari Ini

        </h3>

    </div>

        <div class="card-body p-0">

            <table class="table table-sm mb-0">

                <tr>

                    <td>
                        <span class="badge bg-success">Hadir</span>
                    </td>

                    <td class="text-end">

                        <?= $statusHariIni['Hadir'] ?>

                    </td>

                </tr>

                <tr>

                    <td>
                        <span class="badge bg-warning">
                            Terlambat
                        </span>
                    </td>

                    <td class="text-end">

                        <?= $statusHariIni['Terlambat'] ?>

                    </td>

                </tr>

                <tr>

                    <td>
                        <span class="badge bg-primary">
                            Izin
                        </span>
                    </td>

                    <td class="text-end">

                        <?= $statusHariIni['Izin'] ?>

                    </td>

                </tr>

                <tr>

                    <td>
                        <span class="badge bg-info">
                            Sakit
                        </span>
                    </td>

                    <td class="text-end">

                        <?= $statusHariIni['Sakit'] ?>

                    </td>

                </tr>

                <tr>

                    <td>
                        <span class="badge bg-danger">
                            Alpha
                        </span>
                    </td>

                    <td class="text-end">

                        <?= $statusHariIni['Alpha'] ?>

                    </td>

                </tr>

            </table>

        </div>

    </div>

    </div>

</div>

<script>

const grafik = <?= json_encode($grafik) ?>;

const labels = [];
const data = [];

Object.entries(grafik).forEach(item => {

    labels.push(item[0]);

    data.push(item[1]);

});

new Chart(
    document.getElementById('attendanceChart'),
    {

        type: 'line',

        data: {

            labels: labels,

            datasets: [{

                label: 'Kehadiran',

                data: data,

                borderWidth: 3,

                tension: .35,

                fill: true

            }]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {

                    display: true

                }

            }

        }

    }

);

</script>
<?= $this->endSection() ?>