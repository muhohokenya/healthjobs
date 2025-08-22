<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cache for roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // Define permissions grouped by category
        $permissions = [
            // Job Management Permissions
            'job-management' => [
                'view-job-postings',
                'create-job-postings',
                'edit-job-postings',
                'delete-job-postings',
                'publish-job-postings',
                'archive-job-postings',
                'feature-job-postings',
                'view-job-analytics',
            ],

            // Application Management Permissions
            'application-management' => [
                'view-job-applications',
                'review-applications',
                'shortlist-candidates',
                'schedule-interviews',
                'conduct-interviews',
                'reject-applications',
                'hire-candidates',
                'export-applications',
                'bulk-manage-applications',
            ],

            // User Management Permissions
            'user-management' => [
                'view-user-profiles',
                'edit-user-profiles',
                'verify-medical-credentials',
                'suspend-users',
                'activate-users',
                'delete-users',
                'manage-user-subscriptions',
                'view-user-analytics',
            ],

            // Profile Management Permissions
            'profile-management' => [
                'view-own-profile',
                'edit-own-profile',
                'upload-documents',
                'manage-certifications',
                'update-availability',
                'manage-portfolio',
                'view-application-history',
            ],

            // Communication Permissions
            'communication' => [
                'send-messages',
                'receive-messages',
                'create-notifications',
                'manage-notifications',
                'broadcast-announcements',
            ],

            // Financial Permissions
            'financial' => [
                'view-billing',
                'manage-payments',
                'process-refunds',
                'view-financial-reports',
                'manage-subscription-plans',
                'handle-invoicing',
            ],

            // System Administration
            'system-admin' => [
                'manage-roles',
                'manage-permissions',
                'view-system-logs',
                'manage-system-settings',
                'backup-data',
                'manage-integrations',
                'view-audit-logs',
            ],

            // Content Management
            'content-management' => [
                'manage-blog-posts',
                'manage-resources',
                'moderate-reviews',
                'manage-categories',
                'manage-tags',
                'upload-media',
            ],

            // Reporting & Analytics
            'reporting' => [
                'view-recruitment-analytics',
                'generate-reports',
                'export-data',
                'view-platform-metrics',
                'access-dashboard',
            ],
        ];

        // Define roles and their associated permission groups
        $roles = [
            'super-admin' => [
                'job-management',
                'application-management',
                'user-management',
                'profile-management',
                'communication',
                'financial',
                'system-admin',
                'content-management',
                'reporting',
            ],

            'medical-institution-admin' => [
                'job-management',
                'application-management',
                'user-management',
                'profile-management',
                'communication',
                'financial',
                'content-management',
                'reporting',
            ],

            'hr-manager' => [
                'job-management',
                'application-management',
                'communication',
                'reporting',
            ],

            'recruiter' => [
                'view-job-postings',
                'view-job-applications',
                'review-applications',
                'shortlist-candidates',
                'schedule-interviews',
                'conduct-interviews',
                'send-messages',
                'receive-messages',
                'view-user-profiles',
            ],

            'hiring-manager' => [
                'view-job-postings',
                'edit-job-postings',
                'view-job-applications',
                'review-applications',
                'shortlist-candidates',
                'schedule-interviews',
                'conduct-interviews',
                'hire-candidates',
                'reject-applications',
                'communication',
                'view-recruitment-analytics',
            ],

            'medical-professional' => [
                'profile-management',
                'view-job-postings',
                'view-application-history',
                'send-messages',
                'receive-messages',
                'manage-notifications',
            ],

            'job-seeker' => [
                'view-own-profile',
                'edit-own-profile',
                'upload-documents',
                'manage-certifications',
                'update-availability',
                'manage-portfolio',
                'view-job-postings',
                'view-application-history',
                'send-messages',
                'receive-messages',
                'manage-notifications',
            ],

            'content-moderator' => [
                'content-management',
                'moderate-reviews',
                'view-user-profiles',
                'manage-notifications',
            ],

            'finance-officer' => [
                'financial',
                'view-user-profiles',
                'manage-user-subscriptions',
                'view-financial-reports',
            ],

            'system-administrator' => [
                'system-admin',
                'user-management',
                'view-system-logs',
                'manage-system-settings',
                'backup-data',
            ],
        ];

        // Temporarily disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            // Clear existing roles and permissions
            DB::table('model_has_permissions')->truncate();
            DB::table('model_has_roles')->truncate();
            DB::table('role_has_permissions')->truncate();
            Permission::truncate();
            Role::truncate();

            // Create all permissions
            $allPermissions = [];
            foreach ($permissions as $group => $permissionList) {
                foreach ($permissionList as $permission) {
                    $allPermissions[] = $permission;
                }
            }

            // Remove duplicates and create permissions
            $uniquePermissions = array_unique($allPermissions);
            foreach ($uniquePermissions as $permissionName) {
                Permission::create([
                    'name' => $permissionName,
                    'guard_name' => 'web',
                ]);
            }

            // Create roles and assign permissions
            foreach ($roles as $roleName => $rolePermissions) {
                $role = Role::create([
                    'name' => $roleName,
                    'guard_name' => 'web',
                ]);

                $permissionsToAssign = [];

                foreach ($rolePermissions as $permissionKey) {
                    if (isset($permissions[$permissionKey])) {
                        // It's a permission group
                        $permissionsToAssign = array_merge($permissionsToAssign, $permissions[$permissionKey]);
                    } else {
                        // It's an individual permission
                        $permissionsToAssign[] = $permissionKey;
                    }
                }

                // Remove duplicates and assign permissions
                $permissionsToAssign = array_unique($permissionsToAssign);
                foreach ($permissionsToAssign as $permissionName) {
                    $permission = Permission::where('name', $permissionName)->first();
                    if ($permission) {
                        $role->givePermissionTo($permission);
                    }
                }
            }

            // Assign super-admin role to the first user (if exists)
            $user = User::first();
            if ($user) {
                $user->assignRole('super-admin');
            }

            $this->command->info('Roles and permissions have been seeded successfully!');

        } catch (\Exception $e) {
            $this->command->error('Error seeding roles and permissions: ' . $e->getMessage());
            throw $e;
        } finally {
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        // Clear the cache again after seeding
        app()['cache']->forget('spatie.permission.cache');
    }
}
