<?php

namespace App\Providers;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Builder::macro('toRawSql', function () {
            /** @var Builder $this */
            dd(vsprintf(str_replace(['?'], ['\'%s\''], $this->toSql()), $this->getBindings()));
        });

//        \DB::listen(function ($query) {
//            /**
//             * @var \Illuminate\Database\Events\QueryExecuted $query
//             */
//            \Log::alert('EXECUTED QUERIES: ' . $query->sql, $query->bindings);
//        });
    }
}
