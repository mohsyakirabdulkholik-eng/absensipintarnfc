<?php

namespace App\Models;

use CodeIgniter\Model;

class JenjangModel extends Model
{
    protected $table = 'jenjang';

    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'kode',
        'nama',
        'is_jurusan',
        'created_at'
    ];

    public $timestamps = false;
}