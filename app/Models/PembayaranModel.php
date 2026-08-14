<?php

namespace App\Models;

use CodeIgniter\Model;

class PembayaranModel extends Model
{
    protected $table            = 'pembayaran';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';

    protected $allowedFields = [
        'id_sekolah',
        'id_paket',
        'jumlah',
        'tanggal_pembayaran',
        'metode_pembayaran',
        'bukti_pembayaran',
        'status',
        'catatan',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = false;
}