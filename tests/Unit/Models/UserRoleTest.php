<?php

namespace Tests\Unit\Models;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_be_assigned_a_role()
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'Dentist',
            'slug' => 'dentist',
            'description' => 'Dentist role'
        ]);

        $user->assignRole($role);

        $this->assertTrue($user->roles->contains($role));
        $this->assertEquals(1, $user->roles->count());
    }

    /** @test */
    public function user_can_be_assigned_a_role_by_slug()
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'Assistant',
            'slug' => 'assistant',
            'description' => 'Assistant role'
        ]);

        $user->assignRole('assistant');

        $this->assertTrue($user->roles->contains($role));
        $this->assertEquals(1, $user->roles->count());
    }

    /** @test */
    public function user_can_have_a_role_removed()
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'Receptionist',
            'slug' => 'receptionist',
            'description' => 'Receptionist role'
        ]);

        $user->assignRole($role);
        $this->assertTrue($user->roles->contains($role));

        $user->removeRole($role);
        $user->refresh();
        
        $this->assertFalse($user->roles->contains($role));
        $this->assertEquals(0, $user->roles->count());
    }

    /** @test */
    public function user_can_have_a_role_removed_by_slug()
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'Patient',
            'slug' => 'patient',
            'description' => 'Patient role'
        ]);

        $user->assignRole($role);
        $this->assertTrue($user->roles->contains($role));

        $user->removeRole('patient');
        $user->refresh();
        
        $this->assertFalse($user->roles->contains($role));
        $this->assertEquals(0, $user->roles->count());
    }

    /** @test */
    public function user_can_check_if_has_role()
    {
        $user = User::factory()->create();
        $role = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'Admin role'
        ]);

        $user->assignRole($role);

        $this->assertTrue($user->hasRole('admin'));
        $this->assertFalse($user->hasRole('dentist'));
    }

    /** @test */
    public function user_can_check_if_has_any_role()
    {
        $user = User::factory()->create();
        $adminRole = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'Admin role'
        ]);

        $user->assignRole($adminRole);

        $this->assertTrue($user->hasAnyRole(['admin', 'dentist']));
        $this->assertFalse($user->hasAnyRole(['dentist', 'assistant']));
    }

    /** @test */
    public function user_can_check_if_has_all_roles()
    {
        $user = User::factory()->create();
        $adminRole = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'Admin role'
        ]);
        $dentistRole = Role::create([
            'name' => 'Dentist',
            'slug' => 'dentist',
            'description' => 'Dentist role'
        ]);

        $user->assignRole($adminRole);
        $user->assignRole($dentistRole);

        $this->assertTrue($user->hasAllRoles(['admin', 'dentist']));
        $this->assertFalse($user->hasAllRoles(['admin', 'dentist', 'assistant']));
    }

    /** @test */
    public function user_can_check_if_is_admin()
    {
        // Test with admin column
        $userWithAdminFlag = User::factory()->create(['admin' => true]);
        $this->assertTrue($userWithAdminFlag->isAdmin());

        // Test with admin role
        $userWithAdminRole = User::factory()->create(['admin' => false]);
        $adminRole = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'Admin role'
        ]);
        $userWithAdminRole->assignRole($adminRole);
        $this->assertTrue($userWithAdminRole->isAdmin());

        // Test with neither
        $regularUser = User::factory()->create(['admin' => false]);
        $this->assertFalse($regularUser->isAdmin());
    }
}
