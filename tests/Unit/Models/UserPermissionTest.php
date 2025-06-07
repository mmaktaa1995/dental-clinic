<?php

namespace Tests\Unit\Models;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPermissionTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Role $role1;
    protected Role $role2;
    protected Permission $permission1;
    protected Permission $permission2;
    protected Permission $permission3;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test user
        $this->user = User::factory()->create();

        // Create test roles
        $this->role1 = Role::create([
            'name' => 'Receptionist',
            'slug' => 'receptionist',
            'description' => 'Receptionist role'
        ]);

        $this->role2 = Role::create([
            'name' => 'Dentist',
            'slug' => 'dentist',
            'description' => 'Dentist role'
        ]);

        // Create test permissions
        $this->permission1 = Permission::create([
            'name' => 'View Patient Records',
            'slug' => 'view-patient-records',
            'description' => 'Can view patient records'
        ]);

        $this->permission2 = Permission::create([
            'name' => 'Register Patients',
            'slug' => 'register-patients',
            'description' => 'Can register new patients'
        ]);

        $this->permission3 = Permission::create([
            'name' => 'Create Treatment Plans',
            'slug' => 'create-treatment-plans',
            'description' => 'Can create treatment plans'
        ]);

        // Assign permissions to roles
        $this->role1->permissions()->attach([$this->permission1->id, $this->permission2->id]);
        $this->role2->permissions()->attach([$this->permission1->id, $this->permission3->id]);
    }

    /**
     * Test hasPermission method with a single role.
     */
    public function testHasPermissionWithSingleRole(): void
    {
        // Assign role1 to user
        $this->user->roles()->attach($this->role1);

        // User should have permissions from role1
        $this->assertTrue($this->user->hasPermission('view-patient-records'));
        $this->assertTrue($this->user->hasPermission('register-patients'));
        $this->assertFalse($this->user->hasPermission('create-treatment-plans'));
        $this->assertFalse($this->user->hasPermission('non-existent-permission'));
    }

    /**
     * Test hasPermission method with multiple roles.
     */
    public function testHasPermissionWithMultipleRoles(): void
    {
        // Assign both roles to user
        $this->user->roles()->attach([$this->role1->id, $this->role2->id]);

        // User should have permissions from both roles
        $this->assertTrue($this->user->hasPermission('view-patient-records'));
        $this->assertTrue($this->user->hasPermission('register-patients'));
        $this->assertTrue($this->user->hasPermission('create-treatment-plans'));
        $this->assertFalse($this->user->hasPermission('non-existent-permission'));
    }

    /**
     * Test hasAnyPermission method.
     */
    public function testHasAnyPermission(): void
    {
        // Assign role1 to user
        $this->user->roles()->attach($this->role1);

        // Test with permissions the user has
        $this->assertTrue($this->user->hasAnyPermission(['view-patient-records', 'register-patients']));
        $this->assertTrue($this->user->hasAnyPermission(['view-patient-records', 'create-treatment-plans']));
        $this->assertTrue($this->user->hasAnyPermission(['register-patients', 'create-treatment-plans']));

        // Test with permissions the user doesn't have
        $this->assertFalse($this->user->hasAnyPermission(['create-treatment-plans', 'non-existent-permission']));
        $this->assertFalse($this->user->hasAnyPermission(['non-existent-permission']));
    }

    /**
     * Test hasAllPermissions method.
     */
    public function testHasAllPermissions(): void
    {
        // Assign both roles to user
        $this->user->roles()->attach([$this->role1->id, $this->role2->id]);

        // Test with permissions the user has
        $this->assertTrue($this->user->hasAllPermissions(['view-patient-records', 'register-patients']));
        $this->assertTrue($this->user->hasAllPermissions(['view-patient-records', 'create-treatment-plans']));
        $this->assertTrue($this->user->hasAllPermissions(['register-patients', 'create-treatment-plans']));
        $this->assertTrue($this->user->hasAllPermissions(['view-patient-records', 'register-patients',
        'create-treatment-plans']));

        // Test with some permissions the user doesn't have
        $this->assertFalse($this->user->hasAllPermissions(['view-patient-records', 'non-existent-permission']));
        $this->assertFalse($this->user->hasAllPermissions(['non-existent-permission']));
    }

    /**
     * Test permission inheritance through multiple roles.
     */
    public function testPermissionInheritanceThroughMultipleRoles(): void
    {
        // Create a new permission
        $permission4 = Permission::create([
            'name' => 'View Appointments',
            'slug' => 'view-appointments',
            'description' => 'Can view appointments'
        ]);

        // Create a new role with the new permission
        $role3 = Role::create([
            'name' => 'Assistant',
            'slug' => 'assistant',
            'description' => 'Assistant role'
        ]);
        $role3->permissions()->attach($permission4);

        // Assign all roles to user
        $this->user->roles()->attach([$this->role1->id, $this->role2->id, $role3->id]);

        // User should have permissions from all roles
        $this->assertTrue($this->user->hasPermission('view-patient-records'));
        $this->assertTrue($this->user->hasPermission('register-patients'));
        $this->assertTrue($this->user->hasPermission('create-treatment-plans'));
        $this->assertTrue($this->user->hasPermission('view-appointments'));

        // Test hasAllPermissions with all permissions
        $this->assertTrue($this->user->hasAllPermissions([
            'view-patient-records',
            'register-patients',
            'create-treatment-plans',
            'view-appointments'
        ]));
    }

    /**
     * Test permission checks with no roles assigned.
     */
    public function testPermissionChecksWithNoRoles(): void
    {
        // User has no roles assigned
        $this->assertFalse($this->user->hasPermission('view-patient-records'));
        $this->assertFalse($this->user->hasAnyPermission(['view-patient-records', 'register-patients']));
        $this->assertFalse($this->user->hasAllPermissions(['view-patient-records', 'register-patients']));
    }
}
