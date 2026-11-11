<?php

namespace App\Models;

use CodeIgniter\Model;

class JawabanUjianModel extends Model
{
    protected $table            = 'jawaban_ujian';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType = 'array';

    protected $allowedFields = [
        'id_peserta_ujian',
        'id_soal',
        'id_pilihan',
        'jawaban_esai',
        'is_benar',
        'nilai',
        'waktu_jawab',
    ];

    protected $useTimestamps = true;

    protected $createdField = '';
    protected $updatedField = 'updated_at';
}
