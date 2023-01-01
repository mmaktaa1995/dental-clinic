<?php

namespace Database\Seeders;

use App\Models\AppConfig;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(1)->create();
        if (!AppConfig::query()->where('key', 'usd_exchange')->exists()) {
            AppConfig::query()->create([
                'key' => 'usd_exchange',
                'value' => 6250
            ]);
        }
    }
}
