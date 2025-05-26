<?php

namespace Tests;

use App\Models\User;
use Database\Seeders\TestDatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Skip the data migration during tests
        if (!defined('PHPUNIT_DENTAL_TESTSUITE')) {
            define('PHPUNIT_DENTAL_TESTSUITE', true);
        }
        
        // Enable foreign key constraints for SQLite
        if (\DB::connection() instanceof \Illuminate\Database\SQLiteConnection) {
            \DB::statement('PRAGMA foreign_keys=on;');
        } else {
            // If not using SQLite, ensure we're using the testing database
            config(['database.default' => 'sqlite']);
            config(['database.connections.sqlite.database' => ':memory:']);
        }
        
        // Run migrations and seed the database
        $this->artisan('migrate:fresh');
        $this->seed(TestDatabaseSeeder::class);
    }

    /**
     * Sign in a user for testing.
     *
     * @param  \App\Models\User|null  $user
     * @return $this
     */
    public function signIn($user = null)
    {
        $user = $user ?: User::first();
        Sanctum::actingAs($user, ['*'], 'api');
        
        return $this;
    }
    
    /**
     * Get the test user.
     *
     * @return \App\Models\User
     */
    protected function getTestUser()
    {
        return User::first();
    }
}
