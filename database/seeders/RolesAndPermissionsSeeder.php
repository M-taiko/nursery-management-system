<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions
        $permissions = [
            // System
            'manage system',
            'view dashboard',
            'manage settings',

            // Users
            'manage users',
            'view users',

            // Children
            'manage children',
            'view children',
            'create children',
            'edit children',
            'delete children',

            // Stages & Classrooms
            'manage stages',
            'manage classrooms',

            // Subjects
            'manage subjects',

            // Daily Evaluation
            'create daily evaluation',
            'edit daily evaluation',
            'delete daily evaluation',
            'view daily evaluation',
            'view own children evaluation',

            // Photos
            'upload child photos',
            'view child photos',
            'delete child photos',
            'view own children photos',

            // Behavior
            'create behavior record',
            'view behavior records',
            'view own children behavior',

            // Fees
            'manage fees',
            'view fees',
            'create invoices',
            'manage payments',
            'view own invoices',

            // Notifications
            'send notifications',
            'view notifications',

            // Teachers
            'manage teachers',
            'assign teachers',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->givePermissionTo(Permission::all());

        $teacher = Role::firstOrCreate(['name' => 'Teacher']);
        $teacher->givePermissionTo([
            'view dashboard',
            'view children',
            'create daily evaluation',
            'edit daily evaluation',
            'view daily evaluation',
            'upload child photos',
            'view child photos',
            'create behavior record',
            'view behavior records',
            'view fees',
            'send notifications',
            'view notifications',
        ]);

        $parent = Role::firstOrCreate(['name' => 'Parent']);
        $parent->givePermissionTo([
            'view dashboard',
            'view own children evaluation',
            'view own children photos',
            'view own children behavior',
            'view own invoices',
            'view notifications',
        ]);
    }
}
