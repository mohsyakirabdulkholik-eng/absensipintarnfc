<?php

namespace App\Models;

use CodeIgniter\Model;

class PilihanJawabanModel extends Model
{
    protected $table            = 'pilihan_jawaban';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType = 'array';

    protected $allowedFields = [
        'id_soal',
        'label',
        'jawaban',
        'is_benar',
        'urutan',
    ];

    protected $useTimestamps = true;

    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
