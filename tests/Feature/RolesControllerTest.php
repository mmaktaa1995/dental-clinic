<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolesControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles if they don't exist
        if (!Role::where('slug', 'admin')->exists()) {
            Role::create(['name' => 'Admin', 'slug' => 'admin']);
        }

        // Create necessary permissions for roles management
        $rolePermissions = [
            'view-users' => 'View Users',
            'create-users' => 'Create Users',
            'edit-users' => 'Edit Users',
            'delete-users' => 'Delete Users',
            'view-roles' => 'View Roles',
            'create-roles' => 'Create Roles',
            'edit-roles' => 'Edit Roles',
            'delete-roles' => 'Delete Roles'
        ];

        foreach ($rolePermissions as $slug => $name) {
            Permission::firstOrCreate(['slug' => $slug], ['name' => $name]);
        }

        // Assign all permissions to admin role
        $adminRole = Role::where('slug', 'admin')->first();
        foreach ($rolePermissions as $slug => $name) {
            $adminRole->assignPermission($slug);
        }

        // Create and authenticate a test user with admin role and verified email
        $this->user = User::factory()->create([
            'email_verified_at' => now() // Ensure email is verified
        ]);

        // Create a Sanctum token for the user
        $this->token = $this->user->createToken('test-token')->plainTextToken;

        // Assign admin role to the user
        $adminRole = Role::where('slug', 'admin')->first();
        $this->user->roles()->attach($adminRole);
        $this->user->assignRole('admin');
        $this->actingAs($this->user, 'api');

        // Verify the role was assigned correctly
        $adminRole = Role::where('slug', 'admin')->first();
        if (!$this->user->roles->contains($adminRole->id)) {
            $this->user->roles()->attach($adminRole);
        }
    }

    /** @test */
    public function canListRoles()
    {
        // Create a user and generate a token
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        // Create or get the permission
        $permission = Permission::firstOrCreate(
            ['slug' => 'view-roles'],
            ['name' => 'view-roles']
        );

        // Create a role and assign the permission
        $role = Role::factory()->create();
        $role->permissions()->syncWithoutDetaching([$permission->id]);
        $user->assignRole($role);

        // Make the API request
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', '/api/v1/roles', [
            'search' => '',
            'per_page' => 10,
            'page' => 1,
            'sort_by' => 'name',
            'sort_direction' => 'asc'
        ]);

        // Dump the response content for debugging
        dump('API Response:', $response->getContent());

        // Assert the response status is 200
        $response->assertStatus(200);

        // Check the response structure
        $response->assertJsonStructure([
            'entries' => [
                '*' => [
                    'id',
                    'name',
                    'slug',
                    'created_at',
                    'permissions' => [
                        '*' => [
                            'id',
                            'name',
                            'slug'
                        ]
                    ]
                ]
            ],
            'pagination' => [
                'total',
                'last_page',
                'page',
                'per_page'
            ]
        ]);
    }

    /** @test */
    public function canCreateARole()
    {
        // Create a test permission
        $permission = Permission::create([
            'name' => 'Test Permission',
            'slug' => 'test-permission'
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->postJson('/api/v1/roles/create', [
            'name' => 'Test Role',
            'slug' => 'test-role',
            'permissions' => [$permission->id]
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'تمت العملية بنجاح'
        ]);

        $this->assertDatabaseHas('roles', [
            'name' => 'Test Role',
            'slug' => 'test-role'
        ]);

        // Check if permission was assigned
        $role = Role::where('slug', 'test-role')->first();
        $this->assertTrue($role->permissions->contains($permission->id));
    }

    /** @test */
    public function validatesRequiredFieldsWhenCreatingRole()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->postJson('/api/v1/roles/create', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function canShowARole()
    {
        $role = Role::create([
            'name' => 'Test Role',
            'slug' => 'test-role'
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->getJson("/api/v1/roles/{$role->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $role->id,
            'name' => $role->name,
            'slug' => $role->slug
        ]);
    }

    /** @test */
    public function canUpdateARole()
    {
        $role = Role::create([
            'name' => 'Test Role',
            'slug' => 'test-role'
        ]);

        // Create a test permission
        $permission = Permission::create([
            'name' => 'Test Permission',
            'slug' => 'test-permission'
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->patchJson("/api/v1/roles/{$role->id}", [
            'name' => 'Updated Role',
            'slug' => 'updated-role',
            'permissions' => [$permission->id]
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'تمت العملية بنجاح'
        ]);

        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'name' => 'Updated Role',
            'slug' => 'updated-role'
        ]);

        // Check if permission was assigned
        $updatedRole = Role::find($role->id);
        $this->assertTrue($updatedRole->permissions->contains($permission->id));
    }

    /** @test */
    public function canDeleteARole()
    {
        $role = Role::create([
            'name' => 'Test Role',
            'slug' => 'test-role'
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->deleteJson("/api/v1/roles/{$role->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'تمت العملية بنجاح'
        ]);

        $this->assertDatabaseMissing('roles', [
            'id' => $role->id
        ]);
    }

    /** @test */
    public function preventsDeletingAdminRole()
    {
        $adminRole = Role::where('slug', 'admin')->first();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->deleteJson("/api/v1/roles/{$adminRole->id}");

        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'لا يمكن حذف دور المسؤول'
        ]);

        $this->assertDatabaseHas('roles', [
            'id' => $adminRole->id,
            'slug' => 'admin'
        ]);
    }

    /** @test */
    public function canListPermissions()
    {
        // Create a test permission
        Permission::create([
            'name' => 'Test Permission',
            'slug' => 'test-permission'
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->getJson('/api/v1/permissions');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['id', 'name', 'slug']
        ]);
    }
}
