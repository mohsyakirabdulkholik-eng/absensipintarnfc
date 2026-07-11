<?php

namespace App\Models;

use CodeIgniter\Model;

class SekolahModel extends Model
{
    protected $table = 'sekolah';

    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [

        'nama_sekolah',

        'id_jenjang',

        'npsn',

        'alamat',

        'telepon',

        'email',

        'logo',

        'status',

        'created_at',

        'updated_at',

        'deleted_at'

    ];

    protected $useTimestamps = false;
}