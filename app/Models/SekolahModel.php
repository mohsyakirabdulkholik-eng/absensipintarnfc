<?php

namespace App\Models;

use CodeIgniter\Model;

class SekolahModel extends Model
{
    protected $table = 'sekolah';

    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [

        'nama_sekolah',

        'id_jenjang',

        'npsn',

        'alamat',

        'telepon',

        'email',

        'website',

        'kepala_sekolah',

        'motto',

        'logo',

        'status'

    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    protected $updatedField  = 'updated_at';
}