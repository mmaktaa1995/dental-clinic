<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApiRateLimitingTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Run all migrations and seed the database
        $this->artisan('migrate:fresh');
        $this->artisan('db:seed');
        
        // Create necessary permissions first
        $permissions = [
            ['slug' => 'access-dashboard', 'name' => 'Access Dashboard'],
            ['slug' => 'manage-users', 'name' => 'Manage Users'],
            ['slug' => 'manage-roles', 'name' => 'Manage Roles'],
            ['slug' => 'manage-permissions', 'name' => 'Manage Permissions'],
            ['slug' => 'view-users', 'name' => 'View Users'],
            ['slug' => 'create-users', 'name' => 'Create Users'],
            ['slug' => 'edit-users', 'name' => 'Edit Users'],
            ['slug' => 'delete-users', 'name' => 'Delete Users'],
            ['slug' => 'view-roles', 'name' => 'View Roles'],
            ['slug' => 'create-roles', 'name' => 'Create Roles'],
            ['slug' => 'edit-roles', 'name' => 'Edit Roles'],
            ['slug' => 'delete-roles', 'name' => 'Delete Roles'],
        ];
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                ['name' => $permission['name']]
            );
        }
        
        // Create admin role
        $adminRole = Role::firstOrCreate(
            ['slug' => 'admin'],
            ['name' => 'Admin']
        );
        
        // Assign all permissions to admin role
        $adminRole->permissions()->sync(
            Permission::pluck('id')->toArray()
        );
        
        // Create a test user with known credentials
        $this->user = User::create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        
        // Assign the admin role to the user
        $this->user->roles()->sync([$adminRole->id]);
        
        // Refresh the user model to ensure roles and permissions are loaded
        $this->user->refresh();
        
        // Verify the user was created correctly
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
        
        // Verify the password is correct
        $this->assertTrue(Hash::check('password', $this->user->password));
    }

    /** @test */
    public function it_enforces_rate_limits_on_login_endpoint()
    {
        // Clean up any existing test users
        User::where('email', 'test@example.com')->orWhere('username', 'testuser')->delete();
        
        // Create a test user directly in the test
        $user = User::create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        
        // First, verify we can log in successfully
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest'
        ])->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);
        
        $response->assertStatus(200, 'Login should be successful');
        $responseData = $response->json('data');
        $this->assertArrayHasKey('access_token', $responseData, 'Response should contain access_token');
        $this->assertArrayHasKey('user', $responseData, 'Response should contain user data');
        
        // Store the token for logout
        $token = $responseData['access_token'];
        
        // Log out to clear the session
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest'
        ])->postJson('/api/v1/logout');
        
        // Now try to log in multiple times to trigger rate limiting
        $rateLimited = false;
        $maxAttempts = 65; // Slightly more than the throttle limit of 60
        
        for ($i = 0; $i < $maxAttempts; $i++) {
            $response = $this->withHeaders([
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest'
            ])->postJson('/api/v1/login', [
                'email' => 'test@example.com',
                'password' => 'wrong-password-' . $i,
            ]);
            
            // Check if we've been rate limited
            if ($response->status() === 429) {
                $rateLimited = true;
                break;
            }
            
            // For failed login attempts, we might get a 302 redirect to login page
            // or a 401 unauthorized response
            if (!in_array($response->status(), [302, 401])) {
                $this->fail('Expected 302 or 401 status for failed login attempt, got ' . $response->status());
            }
        }
        
        $this->assertTrue($rateLimited, 'Expected to be rate limited after multiple failed login attempts');
        
        // If we were rate limited, verify that we get a 429 on the next attempt
        if ($rateLimited) {
            $response = $this->withHeaders([
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest'
            ])->postJson('/api/v1/login', [
                'email' => 'test@example.com',
                'password' => 'wrong-password',
            ]);
            
            $response->assertStatus(429);
        }

        // Verify we were rate limited at some point
        $this->assertTrue($rateLimited, 'Expected to be rate limited after multiple failed login attempts');
    }

    /** @test */
    public function it_enforces_rate_limits_on_authenticated_endpoints()
    {
        // First, log in to get a token
        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);
        
        $response->assertStatus(200);
        $responseData = $response->json('data');
        $this->assertArrayHasKey('access_token', $responseData);
        $token = $responseData['access_token'];
        $this->assertNotNull($token, 'Failed to get authentication token');
        
        // Now make multiple requests to an authenticated endpoint
        $rateLimited = false;
        $maxAttempts = 60; // Default Laravel rate limit
        
        for ($i = 0; $i < $maxAttempts + 5; $i++) {
            $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ])->getJson('/api/v1/user');
            
            if ($response->status() === 429) {
                $rateLimited = true;
                break;
            }
            
            $response->assertStatus(200);
        }
        
        $this->assertTrue($rateLimited, 'Expected to be rate limited after multiple authenticated requests');
    }

    /** @test */
    public function it_enforces_stricter_rate_limits_on_file_deletion()
    {
        $user = User::factory()->create([
            'email_verified_at' => now() // Ensure email is verified
        ]);
        $this->actingAs($user, 'api');

        // Try to hit the file deletion endpoint more than allowed (10 times)
        for ($i = 0; $i < 10; $i++) {
            $response = $this->deleteJson("/api/v1/upload/test_folder/test_type");
            $response->assertStatus(404); // Not found expected, but we're testing rate limiting
        }

        // The next request should be rate limited
        $response = $this->deleteJson("/api/v1/upload/test_folder/test_type");
        $response->assertStatus(429); // Too Many Requests
    }
}
