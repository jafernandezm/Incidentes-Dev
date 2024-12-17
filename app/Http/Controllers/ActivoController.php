<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//algoritmos
use App\Algoritmos\AtaqueSeoJapones;
use App\Algoritmos\AtaqueNDSW;
use App\Algoritmos\BusquedaGoogle;
//escaner
use App\Algoritmos\escaners\SucuriEscaner;
use App\Algoritmos\escaners\Bitdefender;
// modelos
use App\Models\ResultadoEscaneo;
use App\Models\Escaneo;
use App\Models\Incidente;
//validaciones
use App\Http\Requests\ActivoRequest;

class ActivoController extends Controller
{
    public function index()
    {
        return view('activo.index');
    }

    public function scanWebsite(ActivoRequest $request)
    {

        $protocol = $request->input('protocol');
        $url = $request->input('url');
        $url = $protocol . '://' . $url;
        $Busqueda = new BusquedaGoogle();
        $ataqueSeoJapones = new AtaqueSeoJapones();
        //tipo 4 Dorks Activo
        $incidenteDorks = Incidente::where('tipo_id', 4)->get();
        $urlcompleta = 'site:' . $url;
        foreach ($incidenteDorks as $incidente) {
            $query = $incidente->contenido;
            $query = $urlcompleta . ' ' . $query;
            $queries[] = $query; // Almacena la query procesada en el array
        }

        $resutaldos = $Busqueda->googleSearch($queries = $queries);
        $ataqueSeoJapones = $ataqueSeoJapones->AtaqueSeoJapones($resutaldos);
        $contadoResultadoSeoJapones = isset($ataqueSeoJapones['results']) && is_array($ataqueSeoJapones['results'])
        ? count($ataqueSeoJapones['results'])
        : 0;

        $ataqueNDSW = new AtaqueNDSW();
        $ataqueNDSWResutaldo = $ataqueNDSW->ataqueNDSW($url);
        //dd($ataqueNDSWResutaldo);
        $contadoResultadoNDSW = isset($ataqueNDSWResutaldo['results']) && is_array($ataqueNDSWResutaldo['results'])
            ? count($ataqueNDSWResutaldo['results'])
            : 0;
        $contadorTotal = $contadoResultadoSeoJapones + $contadoResultadoNDSW;
        // Crear un solo array de detalles
        $cantidadSeo = $ataqueSeoJapones['data']['cantidad'] ?? 0;
        $cantidadNDSW = $ataqueNDSWResutaldo['data']['cantidad'] ?? 0;
        
        $cantidadTotal = $cantidadSeo + $cantidadNDSW;
        $cantidadIncidentesSeo = $ataqueSeoJapones['data']['cantidadIncidentes'] ?? 0;
        $cantidadIncidentesNDSW = $ataqueNDSWResutaldo['data']['cantidadIncidentes'] ?? 0;
        
        $cantidadIncidentesTotal = $cantidadIncidentesSeo + $cantidadIncidentesNDSW;
        
        $detallesUnidos = [
            'IP' => $ataqueNDSWResutaldo['data']['IP'] ?? '', // Valor por defecto si no existe
            'HTTPServer' => $ataqueNDSWResutaldo['data']['HTTPServer'] ?? '',
            'http_status' => $ataqueNDSWResutaldo['data']['http_status'] ?? '',
            'CMS' => $ataqueNDSWResutaldo['data']['CMS'] ?? '',
            'request_config' => $ataqueNDSWResutaldo['data']['request_config'] ?? [],
            'cantidad' => $cantidadTotal, // Usar la suma total
            'cantidadIncidentes' => $cantidadIncidentesTotal, // Usar la suma total
        ];
        $escaneo = new Escaneo();
        $escaneo->url = $url;
        $escaneo->tipo = 'ACTIVO';
        $escaneo->fecha = now();
        $escaneo->detalles = json_encode($detallesUnidos);
        $escaneo->resultado = $contadorTotal;
        //guardar el id del usuario
        $escaneo->user_id = auth()->user()->id;
        $escaneo->save();
        if ($contadoResultadoSeoJapones > 0) {
            $resultados = new ResultadoEscaneo();
            $resultados->escaneo_id = $escaneo->id;
            $resultados->url = $url;
            $resultados->detalle = 'Ataque SEO Japones';
            $resultados->data = json_encode($ataqueSeoJapones['results']);
            $resultados->save();
        }
        if ($contadoResultadoNDSW > 0) {
            $resultados = new ResultadoEscaneo();
            $resultados->escaneo_id = $escaneo->id;
            $resultados->url = $url;
            $resultados->detalle = 'Ataque NDSW';
            $resultados->data = json_encode($ataqueNDSWResutaldo['results']);
            $resultados->save();
        }

        $escaneo = Escaneo::find($escaneo->id);
        // dd($escaneo);
        $detalles = json_decode($escaneo->detalles, true);
        return view('activo.index', [
            'escaneo' => $escaneo,
            'detalles' => $detalles,
        ]);
    }
}
