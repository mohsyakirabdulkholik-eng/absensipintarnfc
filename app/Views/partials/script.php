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

}
?>