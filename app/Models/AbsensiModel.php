<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsensiModel extends Model
{
    protected $table            = 'absensi';

    protected $primaryKey       = 'id';

    protected $returnType       = 'array';

    protected $useSoftDeletes   = false;

    protected $protectFields    = true;

    protected $allowedFields = [

        'id_sekolah',

        'jenis',

        'id_kartu_nfc',

        'tanggal',

        'jam_masuk',

        'jam_pulang',

        'status',

        'terlambat_menit',

        'keterangan',

        'device_id'

    ];

    protected $useTimestamps = true;

    protected $createdField = 'created_at';

    protected $updatedField = 'updated_at';
}