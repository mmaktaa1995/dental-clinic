<?php

namespace Tests\Unit\Models;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_role()
    {
        $role = Role::create([
            'name' => 'Test Role',
            'slug' => 'test-role',
            'description' => 'This is a test role'
        ]);

        $this->assertDatabaseHas('roles', [
            'name' => 'Test Role',
            'slug' => 'test-role',
            'description' => 'This is a test role'
        ]);

        $this->assertEquals('Test Role', $role->name);
        $this->assertEquals('test-role', $role->slug);
        $this->assertEquals('This is a test role', $role->description);
    }

    /** @test */
    public function it_has_many_users()
    {
        $role = Role::create([
            'name' => 'Test Role',
            'slug' => 'test-role',
            'description' => 'This is a test role'
        ]);

        $user = User::factory()->create();
        
        $role->users()->attach($user->id);
        
        $this->assertTrue($role->users->contains($user));
        $this->assertEquals(1, $role->users->count());
    }
}
