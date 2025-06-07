<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class BackupPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if the permission already exists
        $permissionExists = Permission::where('name', 'manage-backups')->exists();

        if (!$permissionExists) {
            // Add the new permission
            $permission = Permission::create([
                'name' => 'manage-backups',
                'slug' => 'manage-backups',
                'description' => 'Manage system backups and restores',
            ]);

            $this->command->info('Added "manage-backups" permission');

            // Get the admin role
            $adminRole = Role::where('name', 'admin')->first();

            if ($adminRole) {
                // Assign the permission to the admin role
                $adminRole->permissions()->attach($permission->id);

                $this->command->info('Assigned "manage-backups" permission to admin role');
            }
        } else {
            $this->command->info('"manage-backups" permission already exists');
        }
    }
}
