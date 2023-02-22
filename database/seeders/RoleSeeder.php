<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role1 = Role::create(['name' => 'Admin']);
        $role2 = Role::create(['name' => 'Contabilidad']);
        $role3 = Role::create(['name' => 'Ventas']);
        $role4 = Role::create(['name' => 'Caja']);

        Permission::create(['name' => 'dashboard']);
        /**
         * permisos para modulo clientes
         */
        Permission::create(['name' => 'client.index']);
        Permission::create(['name' => 'client.view']);
        Permission::create(['name' => 'client.edit']);
        Permission::create(['name' => 'client.getClientid']);
        Permission::create(['name' => 'client.update']);
        Permission::create(['name' => 'client.create']);
        Permission::create(['name' => 'client.store']);
        Permission::create(['name' => 'client.destroy']);

        /**
         * permisos para modulo empresas
         */
        Permission::create(['name' => 'company.index']);
        Permission::create(['name' => 'company.view']);
        Permission::create(['name' => 'company.getCompany']);
        Permission::create(['name' => 'company.getCompanyid']);
        Permission::create(['name' => 'company.store']);
        Permission::create(['name' => 'company.update']);
        Permission::create(['name' => 'company.destroy']);

        /**
         * permisos para modulo api
         */
        Permission::create(['name' => 'getcountry']);
        Permission::create(['name' => 'getDepartment']);
        Permission::create(['name' => 'getmunicipios']);
        Permission::create(['name' => 'geteconomicactivity']);

        $role1->givePermissionTo(Permission::all());
        $role2->givePermissionTo('client.index', 'company.index');
    }
}
