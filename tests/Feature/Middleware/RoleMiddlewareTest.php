<?php

namespace Tests\Feature\Middleware;

use App\Models\User;
use App\Models\Role;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\CheckAnyRole;
use App\Http\Middleware\CheckAllRoles;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        Role::create(['name' => 'Admin', 'slug' => 'admin', 'description' => 'Administrator']);
        Role::create(['name' => 'Dentist', 'slug' => 'dentist', 'description' => 'Dentist']);
        Role::create(['name' => 'Patient', 'slug' => 'patient', 'description' => 'Patient']);
    }

    /** @test */
    public function it_allows_access_to_users_with_required_role()
    {
        // Create a user with the admin role
        $user = User::factory()->create();
        $user->assignRole('admin');
        
        // Debug: Check if the role exists in the database
        $adminRole = Role::where('slug', 'admin')->first();
        $this->assertNotNull($adminRole, 'Admin role does not exist in the database');
        
        // Debug: Check if the role is assigned to the user
        $roleUser = DB::table('role_user')->where('user_id', $user->id)->where('role_id', $adminRole->id)->first();
        $this->assertNotNull($roleUser, 'Role is not properly assigned to the user');
        
        // Debug: Check if hasRole method works correctly
        $this->assertTrue($user->hasRole('admin'), 'hasRole method is not working correctly');

        // Directly test the middleware
        Auth::login($user);
        $request = Request::create('/test', 'GET');
        $middleware = new CheckRole();
        
        // Create a simple next callback
        $next = function ($request) {
            return response()->json(['message' => 'Access granted']);
        };
        
        // Execute the middleware
        $response = $middleware->handle($request, $next, 'admin');
        
        // Assert the response
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['message' => 'Access granted'], json_decode($response->getContent(), true));
    }

    /** @test */
    public function it_denies_access_to_users_without_required_role()
    {
        // Create a user without the admin role
        $user = User::factory()->create();
        $user->assignRole('patient');
        
        // Verify the user doesn't have the admin role
        $this->assertFalse($user->hasRole('admin'));

        // Directly test the middleware
        Auth::login($user);
        $request = Request::create('/test', 'GET');
        $middleware = new CheckRole();
        
        // Create a simple next callback
        $next = function ($request) {
            return response()->json(['message' => 'Access granted']);
        };
        
        // Execute the middleware
        $response = $middleware->handle($request, $next, 'admin');
        
        // Assert the response
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals(['message' => 'Unauthorized. You do not have the required role.'], json_decode($response->getContent(), true));
    }

    /** @test */
    public function it_allows_access_to_users_with_any_of_the_required_roles()
    {
        // Create a user with the dentist role
        $user = User::factory()->create();
        $user->assignRole('dentist');
        
        // Verify the role was assigned correctly
        $this->assertTrue($user->hasRole('dentist'));

        // Directly test the middleware
        Auth::login($user);
        $request = Request::create('/test', 'GET');
        $middleware = new CheckAnyRole();
        
        // Create a simple next callback
        $next = function ($request) {
            return response()->json(['message' => 'Access granted']);
        };
        
        // Execute the middleware
        $response = $middleware->handle($request, $next, 'admin', 'dentist');
        
        // Assert the response
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['message' => 'Access granted'], json_decode($response->getContent(), true));
    }

    /** @test */
    public function it_denies_access_to_users_without_any_of_the_required_roles()
    {
        // Create a user without any of the required roles
        $user = User::factory()->create();
        $user->assignRole('patient');
        
        // Verify the user doesn't have any of the required roles
        $this->assertFalse($user->hasAnyRole(['admin', 'dentist']));

        // Directly test the middleware
        Auth::login($user);
        $request = Request::create('/test', 'GET');
        $middleware = new CheckAnyRole();
        
        // Create a simple next callback
        $next = function ($request) {
            return response()->json(['message' => 'Access granted']);
        };
        
        // Execute the middleware
        $response = $middleware->handle($request, $next, 'admin', 'dentist');
        
        // Assert the response
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals(['message' => 'Unauthorized. You do not have any of the required roles.'], json_decode($response->getContent(), true));
    }

    /** @test */
    public function it_allows_access_to_users_with_all_required_roles()
    {
        // Create a user with both admin and dentist roles
        $user = User::factory()->create();
        $user->assignRole('admin');
        $user->assignRole('dentist');
        
        // Verify the roles were assigned correctly
        $this->assertTrue($user->hasRole('admin'));
        $this->assertTrue($user->hasRole('dentist'));

        // Directly test the middleware
        Auth::login($user);
        $request = Request::create('/test', 'GET');
        $middleware = new CheckAllRoles();
        
        // Create a simple next callback
        $next = function ($request) {
            return response()->json(['message' => 'Access granted']);
        };
        
        // Execute the middleware
        $response = $middleware->handle($request, $next, 'admin', 'dentist');
        
        // Assert the response
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['message' => 'Access granted'], json_decode($response->getContent(), true));
    }

    /** @test */
    public function it_denies_access_to_users_without_all_required_roles()
    {
        // Create a user with only one of the required roles
        $user = User::factory()->create();
        $user->assignRole('admin');
        
        // Verify the user doesn't have all required roles
        $this->assertFalse($user->hasAllRoles(['admin', 'dentist']));

        // Directly test the middleware
        Auth::login($user);
        $request = Request::create('/test', 'GET');
        $middleware = new CheckAllRoles();
        
        // Create a simple next callback
        $next = function ($request) {
            return response()->json(['message' => 'Access granted']);
        };
        
        // Execute the middleware
        $response = $middleware->handle($request, $next, 'admin', 'dentist');
        
        // Assert the response
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals(['message' => 'Unauthorized. You do not have all of the required roles.'], json_decode($response->getContent(), true));
    }
}
