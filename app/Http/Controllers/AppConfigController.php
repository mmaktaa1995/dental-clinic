<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppConfigRequest;
use App\Models\AppConfig;

class AppConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\AppConfigRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(AppConfigRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AppConfig $appConfig
     * @return \Illuminate\Http\Response
     */
    public function show(AppConfig $appConfig)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\AppConfigRequest $request
     * @param  \App\Models\AppConfig               $appConfig
     * @return \Illuminate\Http\Response
     */
    public function update(AppConfigRequest $request, AppConfig $appConfig)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AppConfig $appConfig
     * @return \Illuminate\Http\Response
     */
    public function destroy(AppConfig $appConfig)
    {
        //
    }
}
