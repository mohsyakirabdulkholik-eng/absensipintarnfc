<?php

namespace App\Repositories;

use App\Models\SekolahModel;

class SettingSekolahRepository
{
    protected SekolahModel $sekolahModel;

    public function __construct()
    {
        $this->sekolahModel = new SekolahModel();
    }

    /**
     * Ambil data sekolah berdasarkan ID
     */
    public function getById(int $idSekolah): ?array
    {
        return $this->sekolahModel
            ->select("
                sekolah.*,
                jenjang.nama AS nama_jenjang
            ")
            ->join(
                'jenjang',
                'jenjang.id = sekolah.id_jenjang'
            )
            ->where('sekolah.id', $idSekolah)
            ->first();
    }

    /**
     * Update data sekolah
     */
    public function update(int $idSekolah, array $data): bool
    {
        return $this->sekolahModel
            ->update($idSekolah, $data);
    }
}