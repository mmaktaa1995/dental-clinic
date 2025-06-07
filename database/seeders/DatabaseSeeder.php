<?php

namespace Database\Seeders;

use App\Models\AppConfig;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment('testing')) {
            // Seed teeth data first since other seeders might depend on it
            $this->call(TeethTableSeeder::class);
            $this->call(TestDatabaseSeeder::class);
            return;
        }

        // Seed roles first
        $this->call(RoleSeeder::class);

        // Seed permissions and assign to roles
        $this->call(PermissionSeeder::class);

        // Default seeder for non-testing environments
        \App\Models\User::factory(1)->create();

        if (!AppConfig::query()->where('key', 'usd_exchange')->exists()) {
            AppConfig::query()->create([
                'key' => 'usd_exchange',
                'value' => 6250
            ]);
        }
    }
}
