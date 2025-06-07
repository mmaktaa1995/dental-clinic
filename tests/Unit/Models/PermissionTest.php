<?php

namespace Tests\Unit\Models;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test permission creation.
     */
    public function testPermissionCreation(): void
    {
        $permission = Permission::create([
            'name' => 'View Patient Records',
            'slug' => 'view-patient-records',
            'description' => 'Can view patient records'
        ]);

        $this->assertDatabaseHas('permissions', [
            'name' => 'View Patient Records',
            'slug' => 'view-patient-records',
            'description' => 'Can view patient records'
        ]);

        $this->assertEquals('View Patient Records', $permission->name);
        $this->assertEquals('view-patient-records', $permission->slug);
        $this->assertEquals('Can view patient records', $permission->description);
    }

    /**
     * Test permission-role relationship.
     */
    public function testPermissionRoleRelationship(): void
    {
        // Create a permission
        $permission = Permission::create([
            'name' => 'View Patient Records',
            'slug' => 'view-patient-records',
            'description' => 'Can view patient records'
        ]);

        // Create roles
        $role1 = Role::create([
            'name' => 'Receptionist',
            'slug' => 'receptionist',
            'description' => 'Receptionist role'
        ]);

        $role2 = Role::create([
            'name' => 'Dentist',
            'slug' => 'dentist',
            'description' => 'Dentist role'
        ]);

        // Attach roles to permission
        $permission->roles()->attach([$role1->id, $role2->id]);

        // Assert relationships
        $this->assertCount(2, $permission->roles);
        $this->assertTrue($permission->roles->contains($role1));
        $this->assertTrue($permission->roles->contains($role2));
    }

    /**
     * Test permission-role sync.
     */
    public function testPermissionRoleSync(): void
    {
        // Create a permission
        $permission = Permission::create([
            'name' => 'View Patient Records',
            'slug' => 'view-patient-records',
            'description' => 'Can view patient records'
        ]);

        // Create roles
        $role1 = Role::create([
            'name' => 'Receptionist',
            'slug' => 'receptionist',
            'description' => 'Receptionist role'
        ]);

        $role2 = Role::create([
            'name' => 'Dentist',
            'slug' => 'dentist',
            'description' => 'Dentist role'
        ]);

        $role3 = Role::create([
            'name' => 'Administrator',
            'slug' => 'admin',
            'description' => 'Administrator role'
        ]);

        // Attach roles to permission
        $permission->roles()->attach([$role1->id, $role2->id]);

        // Assert initial relationships
        $this->assertCount(2, $permission->roles);

        // Sync roles (replacing previous roles)
        $permission->roles()->sync([$role2->id, $role3->id]);

        // Refresh the model
        $permission->refresh();

        // Assert updated relationships
        $this->assertCount(2, $permission->roles);
        $this->assertFalse($permission->roles->contains($role1));
        $this->assertTrue($permission->roles->contains($role2));
        $this->assertTrue($permission->roles->contains($role3));
    }

    /**
     * Test permission detachment.
     */
    public function testPermissionDetachment(): void
    {
        // Create a permission
        $permission = Permission::create([
            'name' => 'View Patient Records',
            'slug' => 'view-patient-records',
            'description' => 'Can view patient records'
        ]);

        // Create roles
        $role1 = Role::create([
            'name' => 'Receptionist',
            'slug' => 'receptionist',
            'description' => 'Receptionist role'
        ]);

        $role2 = Role::create([
            'name' => 'Dentist',
            'slug' => 'dentist',
            'description' => 'Dentist role'
        ]);

        // Attach roles to permission
        $permission->roles()->attach([$role1->id, $role2->id]);

        // Assert initial relationships
        $this->assertCount(2, $permission->roles);

        // Detach one role
        $permission->roles()->detach($role1);

        // Refresh the model
        $permission->refresh();

        // Assert updated relationships
        $this->assertCount(1, $permission->roles);
        $this->assertFalse($permission->roles->contains($role1));
        $this->assertTrue($permission->roles->contains($role2));
    }
}
