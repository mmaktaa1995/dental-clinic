<?php

namespace App\Http\Controllers;

use App\Models\AppConfig;
use App\Services\PatientService;
use App\Services\SettingsService;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function __construct()
    {
    }

    public function __invoke()
    {
        return view('vue');
    }

    public function getUsdExchangeRate(): JsonResponse
    {
        $exchangeRate = app(SettingsService::class)->getUsdExchangeRate();
        return response()->json($exchangeRate);
    }

    public function teeth(): JsonResponse
    {
        $teeth = app(SettingsService::class)->getTeeth();
        return response()->json($teeth);
    }
}
