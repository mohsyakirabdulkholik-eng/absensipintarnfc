<script src="<?= base_url('assets/adminlte/plugins/jquery/jquery.min.js')?>"></script>

<script src="<?= base_url('assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js')?>"></script>

<script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.1/js/dataTables.bootstrap5.js"></script>
<script src="<?= base_url('assets/adminlte/dist/js/adminlte.min.js')?>"></script>

<script src="<?= base_url('assets/js/app.js')?>"></script>





<?php
$uri = service('uri')->getSegment(1);

switch ($uri) {

    case 'jurusan':
        echo '<script src="'.base_url('assets/js/jurusan.js').'"></script>';
        break;

    case 'kelas':
        echo '<script src="'.base_url('assets/js/kelas.js').'"></script>';
        break;
    case 'guru':
        echo '<script src="'.base_url('assets/js/guru.js').'"></script>';
        break;
    case 'siswa':
        echo '<script src="'.base_url('assets/js/siswa.js').'"></script>';
        break;
    case 'tahunajaran':
        echo '<script src="'.base_url('assets/js/tahunajaran.js').'"></script>';
        break;
    case 'semester':
        echo '<script src="'.base_url('assets/js/semester.js').'"></script>';
        break;
    case 'harilibur':
        echo '<script src="'.base_url('assets/js/harilibur.js').'"></script>';
        break;
    case 'pengaturanabsensi':
        echo '<script src="'.base_url('assets/js/pengaturanabsensi.js').'"></script>';
        break;
    case 'profile':
        echo '<script src="'.base_url('assets/js/profile.js').'"></script>';
        break;
    case 'setting-sekolah':
        echo '<script src="'.base_url('assets/js/setting-sekolah.js').'"></script>';
        break;
    case 'kartu-nfc':
        echo '<script src="'.base_url('assets/js/kartunfc.js').'"></script>';
        break;
    case 'perangkat':
        echo '<script src="'.base_url('assets/js/perangkat.js').'"></script>
            <script src="'.base_url('assets/plugins/qrcode/qrcode.min.js').'"></script>';
        break;
    case 'kehadiran':
        echo '<script src="'.base_url('assets/js/kehadiran.js').'"></script>';
        break;
    case 'rekap-kehadiran':
        echo '<script src="'.base_url('assets/js/rekap-kehadiran.js').'"></script>';
        break;


} 

?>

<script>
$(document).on("click", ".btn-logout", function () {

    Swal.fire({

        title: "Logout?",

        text: "Anda akan keluar dari sistem.",

        icon: "question",

        showCancelButton: true,

        confirmButtonText: "Logout",

        cancelButtonText: "Batal"

    }).then((result) => {

        if (result.isConfirmed) {

            window.location = "<?= site_url('logout') ?>";

        }

    });

});
</script>