<?php

namespace App\Models;

use CodeIgniter\Model;

class KartuNfcModel extends Model
{
    protected $table = 'kartu_nfc';

    protected $primaryKey = 'id';

    protected $returnType = 'array';

    protected $allowedFields = [
        'uid',
        'id_guru',
        'id_siswa',
        'nomor_kartu',
        'status'
    ];

    protected $useTimestamps = true;

    protected $createdField = 'created_at';

    protected $updatedField = 'updated_at';
}