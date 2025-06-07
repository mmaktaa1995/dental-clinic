<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class SessionTimeoutTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        // Set shorter timeouts for testing
        Config::set('session.lifetime', 2); // 2 minutes
        Config::set('session.idle_timeout', 1); // 1 minute
    }

    /** @test */
    public function redirectsTologinAfterIdleTimeout()
    {
        $user = User::factory()->create();

        // Simulate login
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);

        // Set last activity to more than idle timeout (1 minute) ago
        Session::put('last_activity', time() - 70); // 70 seconds ago

        // Make a new request
        $response = $this->get('/dashboard');

        // Should be redirected to login with error message
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Your session has expired due to inactivity.');

        // User should be logged out
        $this->assertGuest();
    }

    /** @test */
    public function redirectsTologinAfterAbsoluteSessionTimeout()
    {
        $user = User::factory()->create();

        // Simulate login
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);

        // Set session started time to more than session lifetime (2 minutes) ago
        Session::put('session_started_at', time() - 130); // 130 seconds ago

        // Make a new request
        $response = $this->get('/dashboard');

        // Should be redirected to login with error message
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Your session has expired. Please log in again.');

        // User should be logged out
        $this->assertGuest();
    }

    /** @test */
    public function keepsSessionActiveWithActivity()
    {
        $user = User::factory()->create();

        // Simulate login
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);

        // Make another request within idle timeout
        $response = $this->get('/dashboard');
        $response->assertStatus(200);

        // User should still be logged in
        $this->assertAuthenticatedAs($user);
    }
}
