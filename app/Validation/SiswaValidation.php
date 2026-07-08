<?php

namespace App\Validation;

class SiswaValidation
{
    public static function rules(): array
    {
        return [

                'nama'=>[
                    'rules'=>'required',
                    'errors'=>[
                        'required'=>'Nama siswa wajib diisi.'
                    ]
                ],
                'nis'=>[
                    'rules'=>'permit_empty|is_unique[siswa.nis]',
                    'errors'=>[
                        'is_unique'=>'NIS sudah digunakan.'
                    ]
                ],

                'jenis_kelamin'=>[
                    'rules'=>'required'
                ],

                'jabatan'=>[
                    'rules'=>'required'
                ],

                'status'=>[
                    'rules'=>'required'
                ]

            ];
    }
}