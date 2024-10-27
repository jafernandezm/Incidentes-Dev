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
        $role1 = Role::create(['name' => 'admin']);
        $role2 = Role::create(['name' => 'tecnico']);
        $role3 = Role::create(['name' => 'usuario']);
        //$role4 = Role::create(['name' => 'Estudiante']);

        //admin.users
        Permission::create(['name' => 'admin.users'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.create'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.store'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.show'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.edit'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.update'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.destroy'])->syncRoles([$role1]);
        //
        //escaneo
        Permission::create(['name' => 'escaneo'])->syncRoles([$role1, $role2, $role3]);
        Permission::create(['name' => 'escaneo.index'])->syncRoles([$role1, $role2, $role3]);
        Permission::create(['name' => 'escaneo.enviar'])->syncRoles([$role1, $role2, $role3]);
        //filtracion
        // activo
        Permission::create(['name' => 'activo'])->syncRoles([$role1, $role2, $role3]);
        Permission::create(['name' => 'activo.index'])->syncRoles([$role1, $role2, $role3]);
        Permission::create(['name' => 'activo.scanWebsite'])->syncRoles([$role1, $role2, $role3]);
        //pasivo
        Permission::create(['name' => 'pasivo.index'])->syncRoles([$role1, $role2, $role3]);
        Permission::create(['name' => 'pasivo'])->syncRoles([$role1, $role2, $role3]);
        Permission::create(['name' => 'pasivo.scanWebsite'])->syncRoles([$role1, $role2, $role3]);


        //filtracion
        Permission::create(['name' => 'filtracion'])->syncRoles([$role1, $role2, $role3]);
        Permission::create(['name' => 'filtracion.index'])->syncRoles([$role1, $role2, $role3]);
        Permission::create(['name' => 'filtracion.enviar'])->syncRoles([$role1, $role2, $role3]);
        //incidente
        Permission::create(['name' => 'incidente'])->syncRoles([$role1]);
        Permission::create(['name' => 'incidente.index'])->syncRoles([$role1]);
        Permission::create(['name' => 'incidente.enviar'])->syncRoles([$role1]);
        //tipo

    }
}
