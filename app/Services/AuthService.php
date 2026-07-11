<?php

namespace App\Services;

use App\Repositories\AuthRepository;

class AuthService
{
    protected AuthRepository $authRepository;

    public function __construct()
    {
        $this->authRepository = new AuthRepository();
    }

    public function login(string $login, string $password): array
    {
        $user = $this->authRepository->findByLogin($login);

        if (!$user) {
            return [
                'status' => false,
                'message' => 'Username atau email tidak ditemukan.'
            ];
        }

        if ($user['status'] !== 'aktif') {
            return [
                'status' => false,
                'message' => 'Akun tidak aktif.'
            ];
        }

        if (!password_verify($password, $user['password'])) {
            return [
                'status' => false,
                'message' => 'Password salah.'
            ];
        }

        $this->authRepository->updateLastLogin($user['id']);

        session()->set([

            'user_id'        => $user['id'],
            'nama'           => $user['nama'],
            'username'       => $user['username'],
            'id_role'        => $user['id_role'],
            'nama_role'      => $user['nama_role'],
            'id_sekolah'     => $user['id_sekolah'],
            'nama_sekolah'   => $user['nama_sekolah'],
            'id_jenjang'     => $user['id_jenjang'],
            'kode_jenjang'   => $user['kode'],
            'nama_jenjang'   => $user['nama_jenjang'],
            'is_jurusan'     => $user['is_jurusan'],
            'isLoggedIn'     => true

        ]);

        return [
            'status' => true,
            'message' => 'Login berhasil.'
        ];
    }

    public function logout()
    {
        session()->destroy();
    }
    /**
 * Register Admin Sekolah
 */
    public function register(array $data)
    {
        if ($data['password'] !== $data['konfirmasi_password']) {
            throw new \Exception(
                'Konfirmasi password tidak sama.'
            );
        }
        // panjang password
        if (strlen($data['password']) < 8) {
            throw new \Exception(
                'Password minimal 8 karakter.'
            );
        }
        // Cek Username
        if ($this->authRepository->cekUsername($data['username'])) {
            throw new \Exception('Username sudah digunakan.');
        }

        // Cek Email User
        if ($this->authRepository->cekEmail($data['email'])) {
            throw new \Exception('Email sudah digunakan.');
        }

        // Cek NPSN Sekolah
        if ($this->authRepository->cekNpsn($data['npsn'])) {
            throw new \Exception('NPSN sudah terdaftar.');
        }

        // Data Sekolah
        $sekolahData = [

            'nama_sekolah' => trim($data['nama_sekolah']),
            'id_jenjang'   => $data['id_jenjang'],
            'npsn'         => trim($data['npsn']),
            'alamat'       => trim($data['alamat']),
            'telepon'      => trim($data['telepon']),
            'email'        => trim($data['email_sekolah']),
            'logo'         => 'default.png',
            'status'       => 'aktif'

        ];

        // Data User
        $userData = [

            'id_role'      => 2, // Admin Sekolah
            'id_guru'      => null,
            'nama'         => trim($data['nama']),
            'username'     => trim($data['username']),
            'password'     => password_hash(
                $data['password'],
                PASSWORD_DEFAULT
            ),

            'email'        => trim($data['email']),
            'foto'         => 'default.png',
            'no_hp'        => trim($data['telepon']),
            'status'       => 'aktif'

        ];

        return $this->authRepository
            ->register(
                $sekolahData,
                $userData
            );
    }
}