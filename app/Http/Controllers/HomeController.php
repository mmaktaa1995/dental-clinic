<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke()
    {
        $lastFileNumber = Patient::latest()->first()->file_number + 1;
        return view('vue', ['lastFileNumber' => $lastFileNumber]);
    }
}
