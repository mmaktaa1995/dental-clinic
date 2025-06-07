<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::redirect('/', '/admin');

// API Documentation
Route::get('api/documentation', function () {
    $documentation = 'v1';
    $config = array_merge(config('l5-swagger.documentations.v1'), [
        'additional_config_url' => null,
        'validator_url' => null,
        'operations_sort' => null,
    ]);
    $request = request()->merge([
        'documentation' => $documentation,
        'config' => $config
    ]);
    return app('L5Swagger\Http\Controllers\SwaggerController')->api($request);
})->name('l5swagger.api');

Route::get('docs', function () {
    return redirect()->route('l5swagger.api');
})->name('l5swagger.docs');

Route::get('api/docs', function () {
    return redirect()->route('l5swagger.api');
})->name('l5swagger.docs.api');

Route::prefix('')->group(function () {
    Route::get('/{view?}', HomeController::class)->where('view', '(.*)')->name('admin-ui');
});
