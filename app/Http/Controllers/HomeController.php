<?php

namespace App\Http\Controllers;

use App\Models\AppConfig;
use App\Models\Patient;
use App\Services\PatientService;

class HomeController extends Controller
{
    public function __invoke(PatientService $patientService)
    {
        $lastFileNumber = $patientService->getLastFileNumber();
        $exchangeRate = AppConfig::getByKey('usd_exchange');
        return view('vue', compact('lastFileNumber', 'exchangeRate'));
    }
}
