<?php

namespace App\Models;

use CodeIgniter\Model;

class PengaturanAbsensiModel extends Model
{
    protected $table = 'pengaturan_absensi';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id_sekolah',
        'jam_masuk',
        'batas_terlambat',
        'jam_pulang',
        'toleransi_pulang',
        'aktif',
        'keterangan'
    ];

    protected bool $allowEmptyInserts = false;

    protected $skipValidation = true;
}