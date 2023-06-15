<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        
        $superAdmin = Role::create(['name'=>'Super-Admin']);
        $adminRole = Role::create(["name"=>"admin"]);
        $userRole = Role::create(["name"=>"user"]);


        // Permissions for the stated roles 
        $createUserPermission = Permission::create(["name"=>"create users"]);
        $readAllUserPermission = Permission::create(["name"=>"read users"]);
        $readOneUserPermission = Permission::create(["name"=>"read user"]);
        $updateUserPermission = Permission::create(["name"=>"update users"]);
        $deleteUserPermission = Permission::create(["name"=>"delete users"]);


        $superAdmin->syncPermissions([$createUserPermission, $readAllUserPermission, $readOneUserPermission, $updateUserPermission, $deleteUserPermission]);
        $adminRole->syncPermissions([$createUserPermission, $readOneUserPermission, $readAllUserPermission,$updateUserPermission, $deleteUserPermission]);
        $userRole->givePermissionTo($readOneUserPermission);

        $superAdminUser = \App\Models\User::factory()->create([
            'username'=>'Joseph',
            'email'=>'superadmin@123.com',
            'password'=>bcrypt('qazQAZ123'),
            'role'=>'Super-Admin'
        ]);

        $superAdminUser->assignRole($superAdmin);
        
    }
}
