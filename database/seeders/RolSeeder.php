<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role1 = Role::create(['name' => 'Admin']);
        $role2 = Role::create(['name' => 'tecnico']);
        $role3 = Role::create(['name' => 'usuario']);
        //$role4 = Role::create(['name' => 'Estudiante']);

        Permission::create(['name' => 'index.inicio'])->syncRoles([$role1, $role2, $role3]);
        

    }
}
