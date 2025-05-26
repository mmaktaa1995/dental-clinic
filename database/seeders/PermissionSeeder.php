<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the permissions for the dental clinic
        $permissions = [
            // Patient permissions
            [
                'name' => 'View Own Patient Record',
                'slug' => 'view-own-patient-record',
                'description' => 'Ability to view own patient record',
            ],
            [
                'name' => 'View Own Appointments',
                'slug' => 'view-own-appointments',
                'description' => 'Ability to view own appointments',
            ],
            [
                'name' => 'Create Appointment',
                'slug' => 'create-appointment',
                'description' => 'Ability to create an appointment',
            ],
            
            // Receptionist permissions
            [
                'name' => 'View All Appointments',
                'slug' => 'view-all-appointments',
                'description' => 'Ability to view all appointments',
            ],
            [
                'name' => 'Manage Appointments',
                'slug' => 'manage-appointments',
                'description' => 'Ability to create, update, and delete appointments',
            ],
            [
                'name' => 'Register Patients',
                'slug' => 'register-patients',
                'description' => 'Ability to register new patients',
            ],
            [
                'name' => 'View Patient Records',
                'slug' => 'view-patient-records',
                'description' => 'Ability to view patient records',
            ],
            
            // Assistant permissions
            [
                'name' => 'Update Patient Records',
                'slug' => 'update-patient-records',
                'description' => 'Ability to update patient records',
            ],
            [
                'name' => 'View Treatment Plans',
                'slug' => 'view-treatment-plans',
                'description' => 'Ability to view treatment plans',
            ],
            
            // Dentist permissions
            [
                'name' => 'Create Treatment Plans',
                'slug' => 'create-treatment-plans',
                'description' => 'Ability to create treatment plans',
            ],
            [
                'name' => 'Update Treatment Plans',
                'slug' => 'update-treatment-plans',
                'description' => 'Ability to update treatment plans',
            ],
            [
                'name' => 'Record Treatment',
                'slug' => 'record-treatment',
                'description' => 'Ability to record treatment',
            ],
            [
                'name' => 'Prescribe Medication',
                'slug' => 'prescribe-medication',
                'description' => 'Ability to prescribe medication',
            ],
            
            // Admin permissions
            [
                'name' => 'View Audit Logs',
                'slug' => 'view-audit-logs',
                'description' => 'Ability to view audit logs',
            ],
            [
                'name' => 'View System Settings',
                'slug' => 'view-system-settings',
                'description' => 'Ability to view system settings',
            ],
            [
                'name' => 'Update System Settings',
                'slug' => 'update-system-settings',
                'description' => 'Ability to update system settings',
            ],
            [
                'name' => 'Import Users',
                'slug' => 'import-users',
                'description' => 'Ability to import users',
            ],
            [
                'name' => 'Import Appointments',
                'slug' => 'import-appointments',
                'description' => 'Ability to import appointments',
            ],
            [
                'name' => 'Import Services',
                'slug' => 'import-services',
                'description' => 'Ability to import services',
            ],
            [
                'name' => 'Import Expenses',
                'slug' => 'import-expenses',
                'description' => 'Ability to import expenses',
            ],
            [
                'name' => 'Import Patients',
                'slug' => 'import-patients',
                'description' => 'Ability to import patients',
            ],
            [
                'name' => 'Export Users',
                'slug' => 'export-users',
                'description' => 'Ability to export users',
            ],
            [
                'name' => 'Export Appointments',
                'slug' => 'export-appointments',
                'description' => 'Ability to export appointments',
            ],
            [
                'name' => 'Export Services',
                'slug' => 'export-services',
                'description' => 'Ability to export services',
            ],
            [
                'name' => 'Export Expenses',
                'slug' => 'export-expenses',
                'description' => 'Ability to export expenses',
            ],
            [
                'name' => 'View Statistics',
                'slug' => 'view-statistics',
                'description' => 'Ability to view statistics',
            ],
            [
                'name' => 'View Debits',
                'slug' => 'view-debits',
                'description' => 'Ability to view debits',
            ],
            [
                'name' => 'View Expenses',
                'slug' => 'view-expenses',
                'description' => 'Ability to view expenses',
            ],
            [
                'name' => 'View Payments',
                'slug' => 'view-payments',
                'description' => 'Ability to view payments',
            ],
            [
                'name' => 'View Users',
                'slug' => 'view-users',
                'description' => 'Ability to view users',
            ],
            [
                'name' => 'View Roles',
                'slug' => 'view-roles',
                'description' => 'Ability to view roles',
            ],
            [
                'name' => 'View Services',
                'slug' => 'view-services',
                'description' => 'Ability to view services',
            ],
            [
                'name' => 'Create Services',
                'slug' => 'create-services',
                'description' => 'Ability to create services',
            ],
            [
                'name' => 'Update Services',
                'slug' => 'update-services',
                'description' => 'Ability to update services',
            ],
            [
                'name' => 'Delete Services',
                'slug' => 'delete-services',
                'description' => 'Ability to delete services',
            ],
            [
                'name' => 'View Permissions',
                'slug' => 'view-permissions',
                'description' => 'Ability to view permissions',
            ],
            [
                'name' => 'View Statistics',
                'slug' => 'view-statistics',
                'description' => 'Ability to view statistics',
            ]
        ];

        // Create each permission
        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['slug' => $permission['slug']], $permission);
        }

        // Assign permissions to roles
        $this->assignPermissionsToRoles();
    }

    /**
     * Assign permissions to roles.
     */
    private function assignPermissionsToRoles(): void
    {
        // Get roles
        $adminRole = Role::where('slug', 'admin')->first();
        $dentistRole = Role::where('slug', 'dentist')->first();
        $assistantRole = Role::where('slug', 'assistant')->first();
        $receptionistRole = Role::where('slug', 'receptionist')->first();
        $patientRole = Role::where('slug', 'patient')->first();

        if (!$adminRole || !$dentistRole || !$assistantRole || !$receptionistRole || !$patientRole) {
            return; // Roles not found, skip assignment
        }

        // Assign permissions to patient role
        $patientPermissions = [
            'view-own-patient-record',
            'view-own-appointments',
            'create-appointment',
        ];
        
        foreach ($patientPermissions as $permission) {
            $patientRole->assignPermission($permission);
        }

        // Assign permissions to receptionist role
        $receptionistPermissions = [
            'view-all-appointments',
            'manage-appointments',
            'register-patients',
            'view-patient-records',
            'view-debits',
            'view-expenses',
            'view-payments',
        ];
        
        foreach ($receptionistPermissions as $permission) {
            $receptionistRole->assignPermission($permission);
        }

        // Assign permissions to assistant role
        $assistantPermissions = [
            'view-all-appointments',
            'view-patient-records',
            'update-patient-records',
            'view-treatment-plans',
            'view-debits',
            'view-payments',
        ];
        
        foreach ($assistantPermissions as $permission) {
            $assistantRole->assignPermission($permission);
        }

        // Assign permissions to dentist role
        $dentistPermissions = [
            'view-all-appointments',
            'view-patient-records',
            'update-patient-records',
            'view-treatment-plans',
            'create-treatment-plans',
            'update-treatment-plans',
            'record-treatment',
            'prescribe-medication',
            'view-debits',
            'view-payments',
        ];
        
        foreach ($dentistPermissions as $permission) {
            $dentistRole->assignPermission($permission);
        }

        // Admin has all permissions
        $allPermissions = Permission::all()->pluck('slug')->toArray();
        
        // Add view-users and view-roles if not already in all permissions
        if (!in_array('view-users', $allPermissions)) {
            $allPermissions[] = 'view-users';
        }
        if (!in_array('view-roles', $allPermissions)) {
            $allPermissions[] = 'view-roles';
        }
        
        foreach ($allPermissions as $permission) {
            $adminRole->assignPermission($permission);
        }
    }
}
