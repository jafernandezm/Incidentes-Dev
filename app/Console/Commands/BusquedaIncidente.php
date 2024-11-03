<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\PasivoController;
//MODEL INCIDENTE
use App\Models\Incidente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Mail\ResportMailable;
use App\Models\ResultadoEscaneo;
class BusquedaIncidente extends Command
{
    protected $signature = 'website:scan';
    protected $description = 'Escanea el sitio web automáticamente a las 9 a.m. todos los días';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $escaneoPasivo = new PasivoController();
        // Obtiene todos los incidentes de tipo 3
        $incidentesDorks = Incidente::where('tipo_id', 3)->get();

        foreach ($incidentesDorks as $incidente) {
            $dork = $incidente->contenido;
            // Ejecuta el escaneo del sitio web y almacena el resultado
            $escaneoResultado = $escaneoPasivo->scanWebsiteHora($dork);
            // Verifica si $escaneoResultado no está vacío y contiene la propiedad 'resultado'
            
            
            if (!empty($escaneoResultado)) {
                try {
                    // Envía el correo con el objeto incidente y el escaneoResultado
                    $resultados = ResultadoEscaneo::where('escaneo_id',$escaneoResultado->id)->get();
                    Mail::to('contacto@agetic.gob.bo')->send(new ResportMailable($incidente, $escaneoResultado, $resultados));   
                    $this->info("Correo enviado para el incidente con ID {$incidente->id}");
                } catch (\Exception $e) {
                    // Muestra un mensaje de error si ocurre un problema al enviar el correo
                    $this->error('Error al enviar el correo: ' . $e->getMessage());
                }
            } else {
                $this->info("No se enviará correo para el incidente con ID {$incidente->id} ya que no se encontraron resultados.");
            }
        }

        $this->info('Escaneo completado con éxito.');
        return 0; // Retorna un código de éxito
    }
}
