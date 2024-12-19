<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IncidenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $incidentes = [
            [
                'tipo_id' => 1,
                'contenido' => 'https://wjk.hfiiiqkp.shop',
                'descripcion' => 'ataque seo japones',
                'fecha' => '2024-06-22'
            ],
            [
                'tipo_id' => 1,
                'contenido' => 'https://ner.arcdycvz.shop',
                'descripcion' => 'ataque seo japones',
                'fecha' => '2024-06-22'
            ],
            [
                'tipo_id' => 1,
                'contenido' => 'https
                ://aaj.toirifiy.top',
                'descripcion' => 'ataque seo japones',
                'fecha' => '2024-06-22'
            ],
            [
                'tipo_id' => 1,
                'contenido' => 'https://www.umivo.net',
                'descripcion' => 'ataque seo japones',
                'fecha' => '2024-06-22'
            ],
            
            [
                'tipo_id' => 2,
                'contenido' => '<html lang="ja"',
                'descripcion' => 'HTML infectado',
                'fecha' => '2024-06-22'
            ],
            //   <meta property="og:locale" content="ja_JP">
            [
                'tipo_id' => 2,
                'contenido' => '<meta property="og:locale" content="ja_JP">',
                'descripcion' => 'HTML infectado',
                'fecha' => '2024-06-22'
                
            ],
            [
              'tipo_id' => 2,
              'contenido' => '<script>window.location=',
              'descripcion' => 'HTML infectado',
              'fecha' => '2024-06-22'
              ],
            [
                'tipo_id' => 2,
                'contenido' => '<meta http-equiv="refresh" ',
                'descripcion' => 'HTML infectado',
                'fecha' => '2024-06-22'
            ],
            [
                'tipo_id' => 3,
                'contenido' => 'site:gob.bo -filetype:pdf japan',
                'descripcion' => 'dorksPasivo',
                'fecha' => '2024-06-22'
            ],
            [
                'tipo_id' => 4,
                'contenido' => '-filetype:pdf japan',
                'descripcion' => 'dorksActivo',
                'fecha' => '2024-06-22'
            ],
            [
                'tipo_id' => 5,
                'contenido' => '"ndsw===undefined"',
                'descripcion' => 'NDSW',
                'fecha' => '2024-06-22'
            ]
            ,
            [
                'tipo_id' => 5,
                'contenido' => '"ndsw"',
                'descripcion' => 'NDSW',
                'fecha' => '2024-06-22'
            ],
              [
                  'tipo_id' => 5,
                  'contenido' => 'ndsw = true',
                  'descripcion' => 'NDSW',
                  'fecha' => '2024-06-22'
              ],
              [
                'tipo_id' => 2,
                'contenido' => '<b>Hacked By ./Outsiders</b>',
                'descripcion' => 'HTML infectado',
                'fecha' => '2024-06-22'
              ],
              //intext:"hacked by ./outsiders" site:.bo
              //tipo3
              [
                'tipo_id' => 3,
                'contenido' => 'intext:"hacked by ./outsiders" site:.bo',
                'descripcion' => 'dorksPasivo',
                'fecha' => '2024-06-22'
              ]
          ];
          \App\Models\Incidente::factory()->createMany($incidentes);
    }
}



