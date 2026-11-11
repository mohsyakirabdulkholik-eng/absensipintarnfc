<?php

namespace App\Models;

use CodeIgniter\Model;

class UjianModel extends Model
{
    protected $table            = 'ujian';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';

    protected $allowedFields = [
        'id_sekolah',
        'id_guru',
        'id_kelas',
        'id_tahun_ajaran',
        'id_semester',
        'nama_ujian',
        'jenis_ujian',
        'mata_pelajaran',
        'token',
        'tanggal_mulai',
        'waktu_mulai',
        'durasi_menit',
        'kkm',
        'acak_soal',
        'acak_jawaban',
        'status',
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}