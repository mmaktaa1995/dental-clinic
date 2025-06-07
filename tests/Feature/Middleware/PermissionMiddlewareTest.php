<?php

namespace Tests\Feature\Middleware;

use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\CheckAnyPermission;
use App\Http\Middleware\CheckAllPermissions;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Closure;

class PermissionMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected Request $request;
    protected Closure $next;
    protected Response $response;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock request and response
        $this->request = new Request();
        $this->response = new Response('Test Response');
        $this->next = function () {
            return $this->response;
        };
    }

    /**
     * Test the CheckPermission middleware with authorized user.
     */
    public function testCheckPermissionWithAuthorizedUser(): void
    {
        // Create role with permission
        $role = Role::create([
            'name' => 'Receptionist',
            'slug' => 'receptionist',
            'description' => 'Receptionist role'
        ]);

        $permission = Permission::create([
            'name' => 'View Patient Records',
            'slug' => 'view-patient-records',
            'description' => 'Can view patient records'
        ]);

        $role->permissions()->attach($permission);

        // Create user with role
        $user = User::factory()->create();
        $user->roles()->attach($role);

        // Retrieve a fresh instance of the user to ensure correct type
        $authenticatedUser = User::find($user->id);

        // Set the authenticated user
        /** @var \Illuminate\Foundation\Auth\User $authenticatedUser */
        $this->actingAs($authenticatedUser);

        // Create middleware instance
        $middleware = new CheckPermission();

        // Execute middleware
        $response = $middleware->handle($this->request, $this->next, 'view-patient-records');

        // Assert middleware passes
        $this->assertEquals($this->response, $response);
    }

    /**
     * Test the CheckPermission middleware with unauthorized user.
     */
    public function testCheckPermissionWithUnauthorizedUser(): void
    {
        // Create role without the required permission
        $role = Role::create([
            'name' => 'Limited User',
            'slug' => 'limited',
            'description' => 'Limited user role'
        ]);

        $permission = Permission::create([
            'name' => 'Register Patients',
            'slug' => 'register-patients',
            'description' => 'Can register new patients'
        ]);

        $role->permissions()->attach($permission);

        // Create user with role
        $user = User::factory()->create();
        $user->roles()->attach($role);

        // Retrieve a fresh instance of the user to ensure correct type
        $authenticatedUser = User::find($user->id);

        // Set the authenticated user
        /** @var \Illuminate\Foundation\Auth\User $authenticatedUser */
        $this->actingAs($authenticatedUser);

        // Create middleware instance
        $middleware = new CheckPermission();

        // Execute middleware
        $response = $middleware->handle($this->request, $this->next, 'view-patient-records');

        // Assert middleware denies access
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals(
            json_encode(['message' => 'Unauthorized. You do not have the required permission.']),
            $response->getContent()
        );
    }

    /**
     * Test the CheckAnyPermission middleware with authorized user.
     */
    public function testCheckAnyPermissionWithAuthorizedUser(): void
    {
        // Create role with one of the required permissions
        $role = Role::create([
            'name' => 'Receptionist',
            'slug' => 'receptionist',
            'description' => 'Receptionist role'
        ]);

        $permission = Permission::create([
            'name' => 'Register Patients',
            'slug' => 'register-patients',
            'description' => 'Can register new patients'
        ]);

        $role->permissions()->attach($permission);

        // Create user with role
        $user = User::factory()->create();
        $user->roles()->attach($role);

        // Retrieve a fresh instance of the user to ensure correct type
        $authenticatedUser = User::find($user->id);

        // Set the authenticated user
        /** @var \Illuminate\Foundation\Auth\User $authenticatedUser */
        $this->actingAs($authenticatedUser);

        // Create middleware instance
        $middleware = new CheckAnyPermission();

        // Execute middleware
        $response = $middleware->handle($this->request, $this->next, 'view-patient-records', 'register-patients');

        // Assert middleware passes
        $this->assertEquals($this->response, $response);
    }

    /**
     * Test the CheckAnyPermission middleware with unauthorized user.
     */
    public function testCheckAnyPermissionWithUnauthorizedUser(): void
    {
        // Create role without any of the required permissions
        $role = Role::create([
            'name' => 'Limited User',
            'slug' => 'limited',
            'description' => 'Limited user role'
        ]);

        $permission = Permission::create([
            'name' => 'Create Appointment',
            'slug' => 'create-appointment',
            'description' => 'Can create appointments'
        ]);

        $role->permissions()->attach($permission);

        // Create user with role
        $user = User::factory()->create();
        $user->roles()->attach($role);

        // Retrieve a fresh instance of the user to ensure correct type
        $authenticatedUser = User::find($user->id);

        // Set the authenticated user
        /** @var \Illuminate\Foundation\Auth\User $authenticatedUser */
        $this->actingAs($authenticatedUser);

        // Create middleware instance
        $middleware = new CheckAnyPermission();

        // Execute middleware
        $response = $middleware->handle($this->request, $this->next, 'view-patient-records', 'register-patients');

        // Assert middleware denies access
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals(
            json_encode(['message' => 'Unauthorized. You do not have any of the required permissions.']),
            $response->getContent()
        );
    }

    /**
     * Test the CheckAllPermissions middleware with authorized user.
     */
    public function testCheckAllPermissionsWithAuthorizedUser(): void
    {
        // Create role with all required permissions
        $role = Role::create([
            'name' => 'Receptionist',
            'slug' => 'receptionist',
            'description' => 'Receptionist role'
        ]);

        $permission1 = Permission::create([
            'name' => 'View Patient Records',
            'slug' => 'view-patient-records',
            'description' => 'Can view patient records'
        ]);

        $permission2 = Permission::create([
            'name' => 'Register Patients',
            'slug' => 'register-patients',
            'description' => 'Can register new patients'
        ]);

        $role->permissions()->attach([$permission1->id, $permission2->id]);

        // Create user with role
        $user = User::factory()->create();
        $user->roles()->attach($role);

        // Retrieve a fresh instance of the user to ensure correct type
        $authenticatedUser = User::find($user->id);

        // Set the authenticated user
        /** @var \Illuminate\Foundation\Auth\User $authenticatedUser */
        $this->actingAs($authenticatedUser);

        // Create middleware instance
        $middleware = new CheckAllPermissions();

        // Execute middleware
        $response = $middleware->handle($this->request, $this->next, 'view-patient-records', 'register-patients');

        // Assert middleware passes
        $this->assertEquals($this->response, $response);
    }

    /**
     * Test the CheckAllPermissions middleware with unauthorized user.
     */
    public function testCheckAllPermissionsWithUnauthorizedUser(): void
    {
        // Create role with only one of the required permissions
        $role = Role::create([
            'name' => 'Limited User',
            'slug' => 'limited',
            'description' => 'Limited user role'
        ]);

        $permission = Permission::create([
            'name' => 'View Patient Records',
            'slug' => 'view-patient-records',
            'description' => 'Can view patient records'
        ]);

        $role->permissions()->attach($permission);

        // Create user with role
        $user = User::factory()->create();
        $user->roles()->attach($role);

        // Retrieve a fresh instance of the user to ensure correct type
        $authenticatedUser = User::find($user->id);

        // Set the authenticated user
        /** @var \Illuminate\Foundation\Auth\User $authenticatedUser */
        $this->actingAs($authenticatedUser);

        // Create middleware instance
        $middleware = new CheckAllPermissions();

        // Execute middleware
        $response = $middleware->handle($this->request, $this->next, 'view-patient-records', 'register-patients');

        // Assert middleware denies access
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals(
            json_encode(['message' => 'Unauthorized. You do not have all of the required permissions.']),
            $response->getContent()
        );
    }

    /**
     * Test middleware with unauthenticated user.
     */
    public function testMiddlewareWithUnauthenticatedUser(): void
    {
        // Ensure no user is authenticated
        Auth::logout();

        // Create middleware instances
        $permissionMiddleware = new CheckPermission();
        $anyPermissionMiddleware = new CheckAnyPermission();
        $allPermissionsMiddleware = new CheckAllPermissions();

        // Execute middleware
        $response1 = $permissionMiddleware->handle($this->request, $this->next, 'view-patient-records');
        $response2 = $anyPermissionMiddleware->handle(
            $this->request,
            $this->next,
            'view-patient-records',
            'register-patients'
        );
        $response3 = $allPermissionsMiddleware->handle(
            $this->request,
            $this->next,
            'view-patient-records',
            'register-patients'
        );

        // Assert middleware denies access
        $this->assertEquals(401, $response1->getStatusCode());
        $this->assertEquals(json_encode(['message' => 'Unauthenticated.']), $response1->getContent());

        $this->assertEquals(401, $response2->getStatusCode());
        $this->assertEquals(json_encode(['message' => 'Unauthenticated.']), $response2->getContent());

        $this->assertEquals(401, $response3->getStatusCode());
        $this->assertEquals(json_encode(['message' => 'Unauthenticated.']), $response3->getContent());
    }
}
