<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;


class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $admin = Role::create(['name' => 'admin']);
        $doctor = Role::create(['name' => 'doctor']);
        $patient = Role::create(['name' => 'patient']);

        // Create permissions
        $manageUsers = Permission::create(['name' => 'manage users']);
        $manageAppointments = Permission::create(['name' => 'manage appointments']);
        
        // Assign permissions to roles
        $admin->givePermissionTo($manageUsers, $manageAppointments);
        $doctor->givePermissionTo($manageAppointments);
    }
}