<?php

namespace App\Validation;

class JurusanValidation
{
    public static function rules(): array
    {
        return [

            'kode' => [
                'rules' => 'required|min_length[2]|max_length[10]',
                'errors' => [
                    'required' => 'Kode jurusan wajib diisi.'
                ]
            ],

            'nama_jurusan' => [
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama jurusan wajib diisi.'
                ]
            ]

        ];
    }
}