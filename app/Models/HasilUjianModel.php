<?php

namespace App\Models;

use CodeIgniter\Model;

class HasilUjianModel extends Model
{
    protected $table            = 'hasil_ujian';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType = 'array';

    protected $allowedFields = [
        'id_ujian',
        'id_siswa',
        'id_peserta_ujian',
        'jumlah_soal',
        'jumlah_benar',
        'jumlah_salah',
        'nilai',
        'kkm',
        'status',
        'waktu_selesai',
    ];

    protected $useTimestamps = true;

    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}