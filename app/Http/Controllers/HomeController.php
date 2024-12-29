<?php

namespace App\Http\Controllers;

use App\Models\AppConfig;
use App\Services\PatientService;
use Symfony\Component\DomCrawler\Crawler;

class HomeController extends Controller
{
    public function __invoke(PatientService $patientService)
    {
        $lastFileNumber = $patientService->getLastFileNumber();
        $exchangeRate = AppConfig::getByKey('usd_exchange');
        return view('vue', compact('lastFileNumber', 'exchangeRate'));
    }

    public function getUsdExchangeRate()
    {
        $response = \Http::get('https://sp-today.com/en');
        if (!$response->successful()) {
            return response()->json(['error' => 'Failed to fetch the page content.'], 500);
        }

        $html = $response->body();

        $crawler = new Crawler($html);

        $rates = $crawler->filter('div.rate-data')->first();
        // Extract the anchors with link `/currency/`
        $anchorRates = $rates->filter('a[href*="/currency/"]');
        $exchangeRates = collect();
        $anchorRates->each(function (Crawler $node) use (&$exchangeRates) {
            $currencyName = $node->filter('span.name')->first();
            if (!$currencyName) {
                return;
            }

            $exchangeRates[\Str::slug($currencyName->text(), '_')] = $node->filter('div.line-data span.value')->first()->text();
            return $node;
        });

        return response()->json($exchangeRates);
    }
}
