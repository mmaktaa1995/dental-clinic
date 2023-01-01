<?php

namespace App\Http\Controllers;

use App\Models\AppConfig;
use App\Models\Patient;

class HomeController extends Controller
{
    public function __invoke()
    {
        $lastFileNumber = Patient::latest()->first()->file_number + 1;
        $exchangeRate = AppConfig::getByKey('usd_exchange');
        return view('vue', compact('lastFileNumber', 'exchangeRate'));
    }
}
