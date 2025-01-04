<?php

namespace App\Services;

use App\Models\Tooth;
use Symfony\Component\DomCrawler\Crawler;

class SettingsService
{
    public function getUsdExchangeRate()
    {
        if (\Cache::has('exchangeRates')) {
            return response()->json(\Cache::get('exchangeRates'));
        }

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

        \Cache::remember('exchangeRates', now()->addDay(), function () use ($exchangeRates) {
            return $exchangeRates->toArray();
        });

        return response()->json($exchangeRates);
    }


    public function getTeethInfo()
    {
        $data = collect([
            0 => [
                'flag' => true,
                'id' => "1",
                'timage' => "1.1472888961.png",
                'timage_x' => "90",
                'timage_y' => "300",
                'text_x' => "79",
                'text_y' => "325",
                'text_v' => "1",
                'tooth_hover' => "tooth_1.1472889510.png",
            ],
            1 => [
                'flag' => true,
                'id' => "2",
                'timage' => "2.png",
                'timage_x' => "90",
                'timage_y' => "270",
                'text_x' => "79",
                'text_y' => "295",
                'text_v' => "2",
                'tooth_hover' => "2.1473060126.png",
            ],
            2 => [
                'flag' => true,
                'id' => "3",
                'timage' => "3.1473092267.png",
                'timage_x' => "90",
                'timage_y' => "240",
                'text_x' => "80",
                'text_y' => "260",
                'text_v' => "3",
                'tooth_hover' => "3.1473060209.png",
            ],
            3 => [
                'flag' => true,
                'id' => "4",
                'timage' => "4.1473092424.png",
                'timage_x' => "100",
                'timage_y' => "211",
                'text_x' => "89",
                'text_y' => "230",
                'text_v' => "4",
                'tooth_hover' => "4.1473060220.png",
            ],
            4 => [
                'flag' => true,
                'id' => "5",
                'timage' => "5.png",
                'timage_x' => "110",
                'timage_y' => "186",
                'text_x' => "102",
                'text_y' => "200",
                'text_v' => "5",
                'tooth_hover' => "5.1473063113.png",
            ],
            5 => [
                'flag' => true,
                'id' => "6",
                'timage' => "6.png",
                'timage_x' => "126",
                'timage_y' => "162",
                'text_x' => "122",
                'text_y' => "171",
                'text_v' => "6",
                'tooth_hover' => "6.1473063129.png",
            ],
            6 => [
                'flag' => true,
                'id' => "7",
                'timage' => "7.1473063209.png",
                'timage_x' => "143",
                'timage_y' => "144",
                'text_x' => "148",
                'text_y' => "147",
                'text_v' => "7",
                'tooth_hover' => "7.1473063223.png",
            ],
            7 => [
                'flag' => true,
                'id' => "8",
                'timage' => "8.1473065422.png",
                'timage_x' => "166",
                'timage_y' => "140",
                'text_x' => "180",
                'text_y' => "140",
                'text_v' => "8",
                'tooth_hover' => "8.1473065144.png",
            ],
            8 => [
                'flag' => true,
                'id' => "9",
                'timage' => "9.png",
                'timage_x' => "198",
                'timage_y' => "140",
                'text_x' => "213",
                'text_y' => "140",
                'text_v' => "9",
                'tooth_hover' => "9.1473065440.png",
            ],
            9 => [
                'flag' => true,
                'id' => "10",
                'timage' => "10.1473065636.png",
                'timage_x' => "227",
                'timage_y' => "149",
                'text_x' => "247",
                'text_y' => "154",
                'text_v' => "10",
                'tooth_hover' => "10.1473065471.png",
            ],
            10 => [
                'flag' => true,
                'id' => "11",
                'timage' => "11.png",
                'timage_x' => "242",
                'timage_y' => "168",
                'text_x' => "274",
                'text_y' => "177",
                'text_v' => "11",
                'tooth_hover' => "11.1473065749.png",
            ],
            11 => [
                'flag' => true,
                'id' => "12",
                'timage' => "12.1473065786.png",
                'timage_x' => "255",
                'timage_y' => "188",
                'text_x' => "289",
                'text_y' => "200",
                'text_v' => "12",
                'tooth_hover' => "12.1473065780.png",
            ],
            12 => [
                'flag' => true,
                'id' => "13",
                'timage' => "13.1473065820.png",
                'timage_x' => "265",
                'timage_y' => "210",
                'text_x' => "302",
                'text_y' => "228",
                'text_v' => "13",
                'tooth_hover' => "13.1473065844.png",
            ],
            13 => [
                'flag' => true,
                'id' => "14",
                'timage' => "14.1473082978.png",
                'timage_x' => "275",
                'timage_y' => "237",
                'text_x' => "313",
                'text_y' => "259",
                'text_v' => "14",
                'tooth_hover' => "14.1473082971.png",
            ],
            14 => [
                'flag' => true,
                'id' => "15",
                'timage' => "15.1473092514.png",
                'timage_x' => "280",
                'timage_y' => "268",
                'text_x' => "320",
                'text_y' => "290",
                'text_v' => "15",
                'tooth_hover' => "15.1473092486.png",
            ],
            15 => [
                'flag' => true,
                'id' => "16",
                'timage' => "16.1473092564.png",
                'timage_x' => "280",
                'timage_y' => "300",
                'text_x' => "317",
                'text_y' => "330",
                'text_v' => "16",
                'tooth_hover' => "16.h1473144363.png",
            ],
            16 => [
                'flag' => true,
                'id' => "17",
                'timage' => "17.1473093555.png",
                'timage_x' => "280",
                'timage_y' => "350",
                'text_x' => "317",
                'text_y' => "374",
                'text_v' => "17",
                'tooth_hover' => "17.h1473144381.png",
            ],
            17 => [
                'flag' => true,
                'id' => "18",
                'timage' => "18.1473093594.png",
                'timage_x' => "280",
                'timage_y' => "384",
                'text_x' => "318",
                'text_y' => "412",
                'text_v' => "18",
                'tooth_hover' => "18.h1473144391.png",
            ],
            18 => [
                'flag' => true,
                'id' => "19",
                'timage' => "19.png",
                'timage_x' => "277",
                'timage_y' => "417",
                'text_x' => "312",
                'text_y' => "451",
                'text_v' => "19",
                'tooth_hover' => "19.h1473144401.png",
            ],
            19 => [
                'flag' => true,
                'id' => "20",
                'timage' => "20.png",
                'timage_x' => "266",
                'timage_y' => "450",
                'text_x' => "303",
                'text_y' => "487",
                'text_v' => "20",
                'tooth_hover' => "20.h1473144413.png",
            ],
            20 => [
                'flag' => true,
                'id' => "21",
                'timage' => "21.1473093626.png",
                'timage_x' => "252",
                'timage_y' => "480",
                'text_x' => "281",
                'text_y' => "521",
                'text_v' => "21",
                'tooth_hover' => "21.h1473144431.png",
            ],
            21 => [
                'flag' => true,
                'id' => "22",
                'timage' => "22.1473093667.png",
                'timage_x' => "230",
                'timage_y' => "498",
                'text_x' => "254",
                'text_y' => "542",
                'text_v' => "22",
                'tooth_hover' => "22.h1473144445.png",
            ],
            22 => [
                'flag' => true,
                'id' => "23",
                'timage' => "23.1473093885.png",
                'timage_x' => "211",
                'timage_y' => "505",
                'text_x' => "227",
                'text_y' => "554",
                'text_v' => "23",
                'tooth_hover' => "23.h1473144462.png",
            ],
            23 => [
                'flag' => true,
                'id' => "24",
                'timage' => "24.png",
                'timage_x' => "190",
                'timage_y' => "507",
                'text_x' => "202",
                'text_y' => "558",
                'text_v' => "24",
                'tooth_hover' => "24.h1473144474.png",
            ],
            24 => [
                'flag' => true,
                'id' => "25",
                'timage' => "25.png",
                'timage_x' => "170",
                'timage_y' => "507",
                'text_x' => "176",
                'text_y' => "558",
                'text_v' => "25",
                'tooth_hover' => "25.h1473144484.png",
            ],
            25 => [
                'flag' => true,
                'id' => "26",
                'timage' => "26.png",
                'timage_x' => "144",
                'timage_y' => "502",
                'text_x' => "150",
                'text_y' => "550",
                'text_v' => "26",
                'tooth_hover' => "26.h1473144504.png",
            ],
            26 => [
                'flag' => true,
                'id' => "27",
                'timage' => "27.png",
                'timage_x' => "124",
                'timage_y' => "490",
                'text_x' => "130",
                'text_y' => "540",
                'text_v' => "27",
                'tooth_hover' => "27.h1473144515.png",
            ],
            27 => [
                'flag' => true,
                'id' => "28",
                'timage' => "28.1463141496.png",
                'timage_x' => "115",
                'timage_y' => "470",
                'text_x' => "100",
                'text_y' => "510",
                'text_v' => "28",
                'tooth_hover' => "28.h1473144530.png",
            ],
            28 => [
                'flag' => true,
                'id' => "29",
                'timage' => "29.png",
                'timage_x' => "100",
                'timage_y' => "445",
                'text_x' => "85",
                'text_y' => "486",
                'text_v' => "29",
                'tooth_hover' => "29.h1473144543.png",
            ],
            29 => [
                'flag' => true,
                'id' => "30",
                'timage' => "30.png",
                'timage_x' => "90",
                'timage_y' => "414",
                'text_x' => "79",
                'text_y' => "457",
                'text_v' => "30",
                'tooth_hover' => "30.h1473144557.png",
            ],
            30 => [
                "flag" => true,
                "id" => "31",
                "timage" => "31.png",
                "timage_x" => "90",
                "timage_y" => "380",
                "text_x" => "70",
                "text_y" => "410",
                "text_v" => "31",
                "tooth_hover" => "31.h1473144568.png",

            ],
            31 => [
                "flag" => true,
                "id" => "32",
                "timage" => "32.png",
                "timage_x" => "90",
                "timage_y" => "350",
                "text_x" => "70",
                "text_y" => "370",
                "text_v" => "32",
                "tooth_hover" => "32.h1473144577.png",

            ],
        ]);
        $data->chunk(10)->each(function ($chunk) {
            $dataToAdd = [];
            foreach($chunk as $item) {
                $response = \Http::withBody(
                    "type=content&tooth_id={$item['id']}&url1=SmilesByShields.com&key1=5597f7cf373faeae7dbda48cc8912ce2",
                    'application/x-www-form-urlencoded'
                )->post('https://meridiantoothchart.com/tooth/tooth_ajax.php');

                $data = $response->json();
                $dataToAdd[] = [
                    'name' => $data['tooth_name'],
                    'number' => $data['tooth_number'],
                    'image' => "/images/teeth/{$item['id']}.png",
                    'extra' => json_encode([
                        'details' => $data['tdetails'],
                        'timage_x' => $item['timage_x'],
                        'timage_y' => $item['timage_y'],
                        'text_x' => $item['text_x'],
                        'text_y' => $item['text_y'],
                        'meridian' => $data['meridian'],
                        'glands' => $data['glands'],
                        'senseorgan' => $data['senseorgan'],
                        'musculature' => $data['musculature'],
                    ]),
                ];
            }
            Tooth::insert($dataToAdd);
        });
    }
    public function getHoverImages()
    {
        $data = [
            0 => [
                'flag' => true,
                'id' => "1",
                'timage' => "1.1472888961.png",
                'timage_x' => "90",
                'timage_y' => "300",
                'text_x' => "79",
                'text_y' => "325",
                'text_v' => "1",
                'tooth_hover' => "tooth_1.1472889510.png",
            ],
            1 => [
                'flag' => true,
                'id' => "2",
                'timage' => "2.png",
                'timage_x' => "90",
                'timage_y' => "270",
                'text_x' => "79",
                'text_y' => "295",
                'text_v' => "2",
                'tooth_hover' => "2.1473060126.png",
            ],
            2 => [
                'flag' => true,
                'id' => "3",
                'timage' => "3.1473092267.png",
                'timage_x' => "90",
                'timage_y' => "240",
                'text_x' => "80",
                'text_y' => "260",
                'text_v' => "3",
                'tooth_hover' => "3.1473060209.png",
            ],
            3 => [
                'flag' => true,
                'id' => "4",
                'timage' => "4.1473092424.png",
                'timage_x' => "100",
                'timage_y' => "211",
                'text_x' => "89",
                'text_y' => "230",
                'text_v' => "4",
                'tooth_hover' => "4.1473060220.png",
            ],
            4 => [
                'flag' => true,
                'id' => "5",
                'timage' => "5.png",
                'timage_x' => "110",
                'timage_y' => "186",
                'text_x' => "102",
                'text_y' => "200",
                'text_v' => "5",
                'tooth_hover' => "5.1473063113.png",
            ],
            5 => [
                'flag' => true,
                'id' => "6",
                'timage' => "6.png",
                'timage_x' => "126",
                'timage_y' => "162",
                'text_x' => "122",
                'text_y' => "171",
                'text_v' => "6",
                'tooth_hover' => "6.1473063129.png",
            ],
            6 => [
                'flag' => true,
                'id' => "7",
                'timage' => "7.1473063209.png",
                'timage_x' => "143",
                'timage_y' => "144",
                'text_x' => "148",
                'text_y' => "147",
                'text_v' => "7",
                'tooth_hover' => "7.1473063223.png",
            ],
            7 => [
                'flag' => true,
                'id' => "8",
                'timage' => "8.1473065422.png",
                'timage_x' => "166",
                'timage_y' => "140",
                'text_x' => "180",
                'text_y' => "140",
                'text_v' => "8",
                'tooth_hover' => "8.1473065144.png",
            ],
            8 => [
                'flag' => true,
                'id' => "9",
                'timage' => "9.png",
                'timage_x' => "198",
                'timage_y' => "140",
                'text_x' => "213",
                'text_y' => "140",
                'text_v' => "9",
                'tooth_hover' => "9.1473065440.png",
            ],
            9 => [
                'flag' => true,
                'id' => "10",
                'timage' => "10.1473065636.png",
                'timage_x' => "227",
                'timage_y' => "149",
                'text_x' => "247",
                'text_y' => "154",
                'text_v' => "10",
                'tooth_hover' => "10.1473065471.png",
            ],
            10 => [
                'flag' => true,
                'id' => "11",
                'timage' => "11.png",
                'timage_x' => "242",
                'timage_y' => "168",
                'text_x' => "274",
                'text_y' => "177",
                'text_v' => "11",
                'tooth_hover' => "11.1473065749.png",
            ],
            11 => [
                'flag' => true,
                'id' => "12",
                'timage' => "12.1473065786.png",
                'timage_x' => "255",
                'timage_y' => "188",
                'text_x' => "289",
                'text_y' => "200",
                'text_v' => "12",
                'tooth_hover' => "12.1473065780.png",
            ],
            12 => [
                'flag' => true,
                'id' => "13",
                'timage' => "13.1473065820.png",
                'timage_x' => "265",
                'timage_y' => "210",
                'text_x' => "302",
                'text_y' => "228",
                'text_v' => "13",
                'tooth_hover' => "13.1473065844.png",
            ],
            13 => [
                'flag' => true,
                'id' => "14",
                'timage' => "14.1473082978.png",
                'timage_x' => "275",
                'timage_y' => "237",
                'text_x' => "313",
                'text_y' => "259",
                'text_v' => "14",
                'tooth_hover' => "14.1473082971.png",
            ],
            14 => [
                'flag' => true,
                'id' => "15",
                'timage' => "15.1473092514.png",
                'timage_x' => "280",
                'timage_y' => "268",
                'text_x' => "320",
                'text_y' => "290",
                'text_v' => "15",
                'tooth_hover' => "15.1473092486.png",
            ],
            15 => [
                'flag' => true,
                'id' => "16",
                'timage' => "16.1473092564.png",
                'timage_x' => "280",
                'timage_y' => "300",
                'text_x' => "317",
                'text_y' => "330",
                'text_v' => "16",
                'tooth_hover' => "16.h1473144363.png",
            ],
            16 => [
                'flag' => true,
                'id' => "17",
                'timage' => "17.1473093555.png",
                'timage_x' => "280",
                'timage_y' => "350",
                'text_x' => "317",
                'text_y' => "374",
                'text_v' => "17",
                'tooth_hover' => "17.h1473144381.png",
            ],
            17 => [
                'flag' => true,
                'id' => "18",
                'timage' => "18.1473093594.png",
                'timage_x' => "280",
                'timage_y' => "384",
                'text_x' => "318",
                'text_y' => "412",
                'text_v' => "18",
                'tooth_hover' => "18.h1473144391.png",
            ],
            18 => [
                'flag' => true,
                'id' => "19",
                'timage' => "19.png",
                'timage_x' => "277",
                'timage_y' => "417",
                'text_x' => "312",
                'text_y' => "451",
                'text_v' => "19",
                'tooth_hover' => "19.h1473144401.png",
            ],
            19 => [
                'flag' => true,
                'id' => "20",
                'timage' => "20.png",
                'timage_x' => "266",
                'timage_y' => "450",
                'text_x' => "303",
                'text_y' => "487",
                'text_v' => "20",
                'tooth_hover' => "20.h1473144413.png",
            ],
            20 => [
                'flag' => true,
                'id' => "21",
                'timage' => "21.1473093626.png",
                'timage_x' => "252",
                'timage_y' => "480",
                'text_x' => "281",
                'text_y' => "521",
                'text_v' => "21",
                'tooth_hover' => "21.h1473144431.png",
            ],
            21 => [
                'flag' => true,
                'id' => "22",
                'timage' => "22.1473093667.png",
                'timage_x' => "230",
                'timage_y' => "498",
                'text_x' => "254",
                'text_y' => "542",
                'text_v' => "22",
                'tooth_hover' => "22.h1473144445.png",
            ],
            22 => [
                'flag' => true,
                'id' => "23",
                'timage' => "23.1473093885.png",
                'timage_x' => "211",
                'timage_y' => "505",
                'text_x' => "227",
                'text_y' => "554",
                'text_v' => "23",
                'tooth_hover' => "23.h1473144462.png",
            ],
            23 => [
                'flag' => true,
                'id' => "24",
                'timage' => "24.png",
                'timage_x' => "190",
                'timage_y' => "507",
                'text_x' => "202",
                'text_y' => "558",
                'text_v' => "24",
                'tooth_hover' => "24.h1473144474.png",
            ],
            24 => [
                'flag' => true,
                'id' => "25",
                'timage' => "25.png",
                'timage_x' => "170",
                'timage_y' => "507",
                'text_x' => "176",
                'text_y' => "558",
                'text_v' => "25",
                'tooth_hover' => "25.h1473144484.png",
            ],
            25 => [
                'flag' => true,
                'id' => "26",
                'timage' => "26.png",
                'timage_x' => "144",
                'timage_y' => "502",
                'text_x' => "150",
                'text_y' => "550",
                'text_v' => "26",
                'tooth_hover' => "26.h1473144504.png",
            ],
            26 => [
                'flag' => true,
                'id' => "27",
                'timage' => "27.png",
                'timage_x' => "124",
                'timage_y' => "490",
                'text_x' => "130",
                'text_y' => "540",
                'text_v' => "27",
                'tooth_hover' => "27.h1473144515.png",
            ],
            27 => [
                'flag' => true,
                'id' => "28",
                'timage' => "28.1463141496.png",
                'timage_x' => "115",
                'timage_y' => "470",
                'text_x' => "100",
                'text_y' => "510",
                'text_v' => "28",
                'tooth_hover' => "28.h1473144530.png",
            ],
            28 => [
                'flag' => true,
                'id' => "29",
                'timage' => "29.png",
                'timage_x' => "100",
                'timage_y' => "445",
                'text_x' => "85",
                'text_y' => "486",
                'text_v' => "29",
                'tooth_hover' => "29.h1473144543.png",
            ],
            29 => [
                'flag' => true,
                'id' => "30",
                'timage' => "30.png",
                'timage_x' => "90",
                'timage_y' => "414",
                'text_x' => "79",
                'text_y' => "457",
                'text_v' => "30",
                'tooth_hover' => "30.h1473144557.png",
            ],
            30 => [
                "flag" => true,
                "id" => "31",
                "timage" => "31.png",
                "timage_x" => "90",
                "timage_y" => "380",
                "text_x" => "70",
                "text_y" => "410",
                "text_v" => "31",
                "tooth_hover" => "31.h1473144568.png",

            ],
            31 => [
                "flag" => true,
                "id" => "32",
                "timage" => "32.png",
                "timage_x" => "90",
                "timage_y" => "350",
                "text_x" => "70",
                "text_y" => "370",
                "text_v" => "32",
                "tooth_hover" => "32.h1473144577.png",

            ],
        ];

        foreach ($data as $item) {
            $imageContent = file_get_contents("https://meridiantoothchart.com/tooth/admin/tooth_image/" . $item['tooth_hover']);
            file_put_contents(public_path("/images/teeth/{$item['id']}_hover.png"), $imageContent);
        }

    }

    public function getTeethImages()
    {

        $response = \Http::get('http://dental-new1.test/images/teeth.svg');
        if (!$response->successful()) {
            return response()->json(['error' => 'Failed to fetch the page content.'], 500);
        }

        $html = $response->body();

        $crawler = new Crawler($html);

        // Define the `xlink` namespace
        $crawler->registerNamespace('xlink', 'http://www.w3.org/1999/xlink');

// Select all <image> elements
        $images = $crawler->filter('image');

// Iterate through the images and get their attributes
        $imagesData = [];
        $images->each(function (Crawler $node) use (&$imagesData) {
            $imagesData[] = [
                'id' => $node->attr('id'),
                'link' => $node->attr('xlink:href'),
                'x' => $node->attr('x'),
                'y' => $node->attr('y'),
            ];

            $imageContent = file_get_contents($node->attr('xlink:href'));
            file_put_contents(public_path("/images/teeth/{$node->attr('id')}.png"), $imageContent);
        });
        dd($imagesData);
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

        \Cache::remember('exchangeRates', now()->addDay(), function () use ($exchangeRates) {
            return $exchangeRates->toArray();
        });

        return response()->json($exchangeRates);
    }
}
