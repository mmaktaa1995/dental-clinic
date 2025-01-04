<?php

namespace App\Http\Controllers;

use App\Models\AppConfig;
use App\Services\PatientService;
use App\Services\SettingsService;

class HomeController extends Controller
{

    public function __construct()
    {
    }

    public function __invoke(PatientService $patientService)
    {
        $lastFileNumber = $patientService->getLastFileNumber();
        $exchangeRate = AppConfig::getByKey('usd_exchange');
        return view('vue', compact('lastFileNumber', 'exchangeRate'));
    }

    public function getUsdExchangeRate()
    {
        $exchangeRate = app(SettingsService::class)->getUsdExchangeRate();
        return response()->json($exchangeRate);
    }
}
