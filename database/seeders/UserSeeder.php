<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

use App\Models\Tecnico;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'name' => 'jhon fernandez',
            'username' => 'warrior',
            'password' => bcrypt('12345678'),
        ])->assignRole('admin');


        User::create([
            'name' => 'Juan Perez',
            'username' => 'Juan123',
            'password' => bcrypt('12345678'),
        ])->assignRole('tecnico');

        Tecnico::create([
            'user_id' => 2,
            'celular' => '83743777',
            'direccion' => 'Av. America',
            'ci' => '1234568',
        ]);

    }
}