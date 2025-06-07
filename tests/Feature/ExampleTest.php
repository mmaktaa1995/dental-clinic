<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the application returns a successful response.
     *
     * @return void
     */
    public function theApplicationReturnsASuccessfulResponse()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * Test that the database connection is working.
     *
     * @return void
     */
    public function databaseConnection()
    {
        // Check if the migrations table exists and has some migrations
        $migrationCount = \DB::table('migrations')->count();
        $this->assertGreaterThan(0, $migrationCount, 'No migrations found in the database');
    }
}
