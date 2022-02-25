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
     *
     * @return void
     */
    public function run()
    {
        // Clears Laravel's cache of roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        //Permissions
        Permission::create(['name' => 'view recipients']);
        Permission::create(['name' => 'edit recipients']);
        Permission::create(['name' => 'add recipients']);
        Permission::create(['name' => 'destroy recipients']);
        Permission::create(['name' => 'assign recipients']);
        Permission::create(['name' => 'invite recipients']);


        //Create roles and assign created permissions

        $orgContact = Role::create(['name' => 'orgContact']);
        $orgContact->givePermissionTo('view recipients');
        $orgContact->givePermissionTo('edit recipients');
        $orgContact->givePermissionTo('add recipients');
        $orgContact->givePermissionTo('destroy recipients');

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo('view recipients');
        $admin->givePermissionTo('edit recipients');
        $admin->givePermissionTo('add recipients');
        $admin->givePermissionTo('destroy recipients');
        $admin->givePermissionTo('assign recipients');
        $admin->givePermissionTo('invite recipients');

        $superadmin = Role::create(['name' => 'super-admin']);
        $superadmin->givePermissionTo(Permission::all());


    }
}
