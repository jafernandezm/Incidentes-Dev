<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $tipos = [
      //nombre, descripcion
      //Url Infectada , Html Infectado, Dorks Pasivo, Dorks Activo, Ataque NDSW
      [
        'nombre' => 'Url Infectada',
        'descripcion' => 'buscar'
      ],
      [
        'nombre' => 'Html Infectado',
        'descripcion' => 'buscar'
      ],
      [
        'nombre' => 'Dorks Pasivo',
        'descripcion' => 'buscar'
      ],
      [
        'nombre' => 'Dorks Activo',
        'descripcion' => 'buscar'
      ],
      [
        'nombre' => 'Ataque NDSW',
        'descripcion' => 'buscar'
      ]

    ]; // urlInfectada, htmlInfectado, dorks, NDSW

    \App\Models\Tipo::factory()->createMany($tipos);
  }
}
