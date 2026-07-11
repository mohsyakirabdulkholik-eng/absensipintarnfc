<?php

namespace App\Models;

use CodeIgniter\Model;

class HariLiburModel extends Model
{
    protected $table = 'hari_libur';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'id_sekolah',
        'tanggal',
        'nama_libur',
        'keterangan'
    ];

    protected bool $allowEmptyInserts = false;
    protected $skipValidation = true;
}