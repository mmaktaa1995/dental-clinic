<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Permission;
use App\Models\Role;

class AddUserAndRolePermissions extends Migration
{
    /**
     * Run the migration.
     *
     * @return void
     */
    public function up()
    {
        // Define user management permissions
        $userPermissions = [
            [
                'name' => 'View Users',
                'slug' => 'view-users',
                'description' => 'Can view users list and details'
            ],
            [
                'name' => 'Create Users',
                'slug' => 'create-users',
                'description' => 'Can create new users'
            ],
            [
                'name' => 'Edit Users',
                'slug' => 'edit-users',
                'description' => 'Can edit existing users'
            ],
            [
                'name' => 'Delete Users',
                'slug' => 'delete-users',
                'description' => 'Can delete users'
            ],
        ];

        // Define role management permissions
        $rolePermissions = [
            [
                'name' => 'View Roles',
                'slug' => 'view-roles',
                'description' => 'Can view roles list and details'
            ],
            [
                'name' => 'Create Roles',
                'slug' => 'create-roles',
                'description' => 'Can create new roles'
            ],
            [
                'name' => 'Edit Roles',
                'slug' => 'edit-roles',
                'description' => 'Can edit existing roles'
            ],
            [
                'name' => 'Delete Roles',
                'slug' => 'delete-roles',
                'description' => 'Can delete roles'
            ],
        ];

        // Create permissions
        $allPermissions = array_merge($userPermissions, $rolePermissions);
        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                [
                    'name' => $permission['name'],
                    'description' => $permission['description']
                ]
            );
        }

        // Assign all permissions to admin role
        $adminRole = Role::where('slug', 'admin')->first();
        if ($adminRole) {
            $permissions = Permission::whereIn('slug', array_column($allPermissions, 'slug'))->get();
            $adminRole->permissions()->syncWithoutDetaching($permissions->pluck('id')->toArray());
        }
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        // Remove the permissions
        $slugs = [
            'view-users', 'create-users', 'edit-users', 'delete-users',
            'view-roles', 'create-roles', 'edit-roles', 'delete-roles'
        ];

        Permission::whereIn('slug', $slugs)->delete();
    }
}
