<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $adminRole = Role::create(["name"=>"admin"]);
        $userRole = Role::create(["name"=>"user"]);


        // Permissions for the stated roles 
        $createPermission = Permission::create(["name"=>"create users"]);
        $readAllPermission = Permission::create(["name"=>"read users"]);
        $readOnePermission = Permission::create(["name"=>"read user"]);
        $updatePermission = Permission::create(["name"=>"update users"]);
        $deletePermission = Permission::create(["name"=>"delete users"]);


        $adminRole->syncPermissions([$createPermission, $readAllPermission, $readOnePermission, $updatePermission, $deletePermission]);
        $userRole->givePermissionTo($readOnePermission);
        
    }
}
