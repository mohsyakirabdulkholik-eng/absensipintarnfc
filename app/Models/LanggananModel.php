<?php

namespace App\Models;

use CodeIgniter\Model;

class LanggananModel extends Model
{
    protected $table            = 'langganan';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'id_sekolah',
        'id_paket',
        'tanggal_mulai',
        'tanggal_berakhir',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;

    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}