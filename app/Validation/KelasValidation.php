<?php

namespace App\Validation;

class KelasValidation
{
    public static function rules(): array
    {
        return [

            'kode' => [
                'rules' => 'required|min_length[2]|max_length[10]',
                'errors' => [
                    'required' => 'Kode kelas wajib diisi.'
                ]
            ],

            'nama_kelas' => [
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama kelas wajib diisi.'
                ]
            ]

        ];
    }
}