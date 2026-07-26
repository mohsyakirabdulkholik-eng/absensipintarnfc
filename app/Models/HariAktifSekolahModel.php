<?php

namespace App\Models;

use CodeIgniter\Model;

class HariAktifSekolahModel extends Model
{
    protected $table = 'hari_aktif_sekolah';

    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $useSoftDeletes = false;

    protected $protectFields = true;

    protected $allowedFields = [
        'id_sekolah',
        'hari',
        'aktif'
    ];

    protected $useTimestamps = false;
}