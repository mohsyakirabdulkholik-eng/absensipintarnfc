<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Services\SubscriptionService;

class LanggananController extends BaseController
{
    protected SubscriptionService $subscriptionService;

    public function __construct()
    {
        $this->subscriptionService = new SubscriptionService();
    }

    public function index()
    {
        $langganan = $this->subscriptionService
            ->getAllWithDetail();

        return view('superadmin/langganan/index', [
            'title'     => 'Langganan',
            'langganan' => $langganan,
        ]);
    }
}