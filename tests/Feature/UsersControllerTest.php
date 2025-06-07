<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsersControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles if they don't exist
        if (!Role::where('slug', 'admin')->exists()) {
            Role::create(['name' => 'Admin', 'slug' => 'admin']);
        }

        // Create necessary permissions for users management
        $userPermissions = [
            'view-users' => 'View Users',
            'create-users' => 'Create Users',
            'edit-users' => 'Edit Users',
            'delete-users' => 'Delete Users',
            'view-roles' => 'View Roles',
            'create-roles' => 'Create Roles',
            'edit-roles' => 'Edit Roles',
            'delete-roles' => 'Delete Roles'
        ];

        foreach ($userPermissions as $slug => $name) {
            Permission::firstOrCreate(['slug' => $slug], ['name' => $name]);
        }

        // Assign all permissions to admin role
        $adminRole = Role::where('slug', 'admin')->first();
        foreach ($userPermissions as $slug => $name) {
            $adminRole->assignPermission($slug);
        }

        // Create and authenticate a test user with admin role and verified email
        $this->user = User::factory()->create([
            'email_verified_at' => now() // Ensure email is verified
        ]);
        $this->user->assignRole('admin');
        $this->actingAs($this->user, 'api');

        // Verify the role was assigned correctly
        $adminRole = Role::where('slug', 'admin')->first();
        if (!$this->user->roles->contains($adminRole->id)) {
            $this->user->roles()->attach($adminRole);
        }
    }

    /** @test */
    public function canListUsers()
    {
        // Create a personal access token for the user
        $token = $this->user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson('/api/v1/users', [
            'page' => 1,
            'per_page' => 15,
            'sort_by' => 'created_at',
            'sort_direction' => 'desc'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'entries',
            'pagination' => [
                'total',
                'page',
                'last_page',
                'per_page'
            ]
        ]);
    }

    /** @test */
    public function canCreateAUser()
    {
        $token = $this->user->createToken('test-token')->plainTextToken;
        $uniqueId = uniqid();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson('/api/v1/users/create', [
            'name' => 'Test User',
            'username' => 'testuser_' . $uniqueId,
            'email' => 'test_' . $uniqueId . '@example.com',
            'password' => 'password123',
            'roles' => [Role::where('slug', 'admin')->first()->id]
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'تمت العملية بنجاح'
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);

        // Check if role was assigned
        $user = User::where('email', 'test@example.com')->first();
        $this->assertTrue($user->hasRole('admin'));
    }

    /** @test */
    public function validatesRequiredFieldsWhenCreatingUser()
    {
        $token = $this->user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson('/api/v1/users/create', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    /** @test */
    public function canShowAUser()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $this->user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->getJson("/api/v1/users/{$user->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ]);
    }

    /** @test */
    public function canUpdateAUser()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $token = $this->user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->patchJson("/api/v1/users/{$user->id}", [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'roles' => [Role::where('slug', 'admin')->first()->id]
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'تمت العملية بنجاح'
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
        ]);

        // Check if role was assigned
        $updatedUser = User::find($user->id);
        $this->assertTrue($updatedUser->hasRole('admin'));
    }

    /** @test */
    public function canDeleteAUser()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');
        $userId = $user->id;

        $token = $this->user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->deleteJson("/api/v1/users/{$userId}");

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'تمت العملية بنجاح'
        ]);

        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }

    /** @test */
    public function preventsDeletingSelf()
    {
        $userId = $this->user->id;
        $token = $this->user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->deleteJson("/api/v1/users/{$userId}");

        $response->assertStatus(403);
        $response->assertJson([
            'message' => 'لا يمكن حذف المستخدم الحالي'
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $userId
        ]);
    }
}
