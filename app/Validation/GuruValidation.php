<?php

namespace App\Validation;

class GuruValidation
{
    public static function rules(): array
    {
        return [

                'nama'=>[
                    'rules'=>'required',
                    'errors'=>[
                        'required'=>'Nama guru wajib diisi.'
                    ]
                ],
                'nip'=>[
                    'rules'=>'permit_empty|is_unique[guru.nip]',
                    'errors'=>[
                        'is_unique'=>'NIP sudah digunakan.'
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