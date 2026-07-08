<?php

namespace App\Core\Services;

use CodeIgniter\Database\Config;

class AuditLogService
{
    protected $db;

    public function __construct()
    {
        $this->db = Config::connect();
    }

    public function log(
        string $modul,
        string $aktivitas,
        array $lama=[],
        array $baru=[]
    )
    {

        $this->db
            ->table('audit_logs')
            ->insert([

                'id_user'=>session('user_id'),

                'modul'=>$modul,

                'aktivitas'=>$aktivitas,

                'data_lama'=>json_encode($lama),

                'data_baru'=>json_encode($baru),

                'ip_address'=>service('request')->getIPAddress(),

                'user_agent'=>service('request')
                ->getUserAgent()
                ->getAgentString()

            ]);

    }

}