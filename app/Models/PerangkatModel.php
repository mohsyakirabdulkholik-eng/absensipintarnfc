<?php

namespace App\Models;

use CodeIgniter\Model;

class PerangkatModel extends Model
{
    protected $table            = 'perangkat';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'id_sekolah',
        'nama_perangkat',

        'jenis_perangkat',
        'hardware_id',

        'android_id',
        'merk',
        'model',
        'versi_android',
        'token',
        'status',
        'last_sync'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}