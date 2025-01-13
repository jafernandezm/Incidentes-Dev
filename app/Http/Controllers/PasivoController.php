<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Algoritmos\BusquedaGoogle;
use App\Algoritmos\AtaqueSeoJapones;
//Resultados_Escaneos
use App\Models\ResultadoEscaneo;
use App\Models\Escaneo;
//Request 
use App\Http\Requests\PasivoRequest;

class PasivoController extends Controller
{
    public function index()
    {
        return view('pasivo.index');
    }

    public function scanWebsite(PasivoRequest $request)
    {
        $Busqueda = new BusquedaGoogle();
        $ataqueSeoJapones = new AtaqueSeoJapones();
        $numResultsControl = $request->cantidad;
        $dorks = $request->dorks;
        $query = trim($dorks);
        if (!empty($request->excludeSitesHidden)) {
            $excludedSites = array_map(function ($site) {
                return '-site:' . trim($site);
            }, explode(',', $request->excludeSitesHidden));
            $query .= ' ' . implode(' ', $excludedSites);
        }
        $resultados = $Busqueda->googleSearch($queries = [$query], $timeout = 30, $numResults = $numResultsControl);
        //$ataqueSeoJaponesTodo = $ataqueSeoJapones->AtaqueSeoJapones($resutaldos);
        // Procesamos el ataque SEO Japonés
        $ataqueSeoJaponesTodo = $ataqueSeoJapones->AtaqueSeoJapones($resultados);
        //dd($ataqueSeoJaponesTodo);
        $ataqueSeoJaponesResults = $ataqueSeoJaponesTodo['results'] ?? [];
        $ataqueSeoJaponesData = $ataqueSeoJaponesTodo['data'] ?? [];

        // Contamos los resultados
        $contadoResultado = count($ataqueSeoJaponesResults);

        // Creamos el escaneo
        $escaneo = new Escaneo;
        $escaneo->url = $request->dorks;
        $escaneo->tipo = 'PASIVO';
        $escaneo->fecha = date('Y-m-d H:i:s');
        $escaneo->resultado = $contadoResultado;
        $escaneo->user_id = auth()->user()->id;
        //dd($escaneo);
        // Si hay data y resultados, guardamos ambos
        if (!empty($ataqueSeoJaponesData) && $contadoResultado > 0) {
            $escaneo->detalles = json_encode($ataqueSeoJaponesData);
            $escaneo->save();

            // Crear el resultado solo si hay datos y resultados
            $resultado = new ResultadoEscaneo();
            $resultado->escaneo_id = $escaneo->id;
            $resultado->url = $request->dorks;
            $resultado->detalle = 'Ataque SEO Japones';
            $resultado->data = json_encode($ataqueSeoJaponesResults);
            $resultado->save();
        } else {
            // Si no hay datos ni resultados, solo guardamos el escaneo
            $escaneo->detalles = json_encode($ataqueSeoJaponesData);
            $escaneo->save();
        }
        $escaneo = Escaneo::where('id', $escaneo->id)->first();
        $detalles = json_decode($escaneo->detalles, true);
        return view('pasivo.index', [
            'escaneo' => $escaneo,
            'detalles' => $detalles
        ]);
    }

    
    public function scanWebsiteHora($requestDorks, $requestnumResultsControl=60)
    {
        $Busqueda = new BusquedaGoogle();
        $ataqueSeoJapones = new AtaqueSeoJapones();
        $numResultsControl = $requestnumResultsControl;
        $dorks = $requestDorks;
        $query = trim($dorks);
        // if (!empty($request->excludeSitesHidden)) {
        //     $excludedSites = array_map(function ($site) {
        //         return '-site:' . trim($site);
        //     }, explode(',', $request->excludeSitesHidden));
        //     $query .= ' ' . implode(' ', $excludedSites);
        // }
        $resultados = $Busqueda->googleSearch($queries = [$query], $timeout = 30, $numResults = $numResultsControl);
        //$ataqueSeoJaponesTodo = $ataqueSeoJapones->AtaqueSeoJapones($resutaldos);
        // Procesamos el ataque SEO Japonés
        $ataqueSeoJaponesTodo = $ataqueSeoJapones->AtaqueSeoJapones($resultados);
        //dd($ataqueSeoJaponesTodo);
        $ataqueSeoJaponesResults = $ataqueSeoJaponesTodo['results'] ?? [];
        $ataqueSeoJaponesData = $ataqueSeoJaponesTodo['data'] ?? [];

        // Contamos los resultados
        $contadoResultado = count($ataqueSeoJaponesResults);

        // Creamos el escaneo
        $escaneo = new Escaneo;
        $escaneo->url = $requestDorks;
        $escaneo->tipo = 'PASIVO';
        $escaneo->fecha = date('Y-m-d H:i:s');
        $escaneo->resultado = $contadoResultado;
         // El usuario está autenticado, obtenemos su ID
       
        $escaneo->user_id = 1;
        // Si hay data y resultados, guardamos ambos
        if (!empty($ataqueSeoJaponesData) && $contadoResultado > 0) {
            $escaneo->detalles = json_encode($ataqueSeoJaponesData);
            $escaneo->save();

            // Crear el resultado solo si hay datos y resultados
            $resultado = new ResultadoEscaneo();
            $resultado->escaneo_id = $escaneo->id;
            $resultado->url = $requestDorks;
            $resultado->detalle = 'Ataque SEO Japonés';
            $resultado->data = json_encode($ataqueSeoJaponesResults);
            $resultado->save();
        } else {
            // Si no hay datos ni resultados, solo guardamos el escaneo
            $escaneo->detalles = json_encode($ataqueSeoJaponesData);
            $escaneo->save();
        }
        //devolver si se encontro algo
        if($contadoResultado > 0){
            $escaneo = Escaneo::where('id', $escaneo->id)->first();
            return $escaneo;
        }
        return [];   
    }
}
