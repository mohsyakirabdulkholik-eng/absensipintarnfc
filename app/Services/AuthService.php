<?php

namespace App\Services;

use App\Repositories\UserRepository;

class AuthService
{
    protected UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function login(string $login, string $password): array
    {
        $user = $this->userRepository->findByLogin($login);

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

        $this->userRepository->updateLastLogin($user['id']);

        session()->set([
            'user_id'      => $user['id'],
            'nama'         => $user['nama'],
            'username'     => $user['username'],
            'id_role'      => $user['id_role'],
            'id_sekolah'   => $user['id_sekolah'],
            'isLoggedIn'   => true
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
}