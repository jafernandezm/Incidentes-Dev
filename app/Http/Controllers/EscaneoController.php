<?php

namespace App\Http\Controllers;

use App\Models\Escaneo;
use Illuminate\Http\Request;
use App\Models\Filtracion;
//resultado
use App\Models\ResultadoEscaneo;
class EscaneoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $escaneos = Escaneo::orderBy('created_at', 'desc')->get();
        return view('resultado.index', [
            'escaneos' => $escaneos
        ]);
    }

    public function enviar($id)
    {
        // Obtén el escaneo basado en el ID
        $escaneo = Escaneo::find($id);
        
        // Verifica si el escaneo existe
        if (!$escaneo) {
            return redirect()->route('escaneo.index')->with('error', 'Escaneo no encontrado');
        }
    
        // Obtén el tipo de escaneo
        $tipo = $escaneo->tipo;
        
        if ($tipo == 'filtraciones') {
            // Si es del tipo 'filtraciones', obtenemos los datos filtrados
            $datosFiltrados = Filtracion::where('escaneo_id', $escaneo->id)->get()->toArray();
    
            return view('filtracion.table', [
                'resultados' => $datosFiltrados,
            ]);
        } elseif ($tipo == 'PASIVO') {
            // Si es del tipo 'PASIVO'
            
            $detalles = json_decode($escaneo->detalles, true);
            return view('pasivo.resultado', [
                'escaneo' => $escaneo,
                'detalles' => $detalles,
            ]);
        } elseif ($tipo == 'ACTIVO') {
            // Si es del tipo 'ACTIVO', cargamos también las relaciones con 'resultados'
            $escaneoConResultados = Escaneo::where('id', $escaneo->id)->with('resultados')->first();
            $detalles = json_decode($escaneo->detalles, true);
            return view('activo.resultado', [
                'escaneo' => $escaneoConResultados,
                'detalles' => $detalles,
                'resultados' => $escaneoConResultados->resultados->toArray(),
            ]);
        } else {
            // Redirigir a una vista por defecto si no se encuentra ninguno
            return redirect()->route('escaneo.index')->with('error', 'No se encontraron resultados');
        }
    }
    
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Encuentra los resultados del escaneo basados en el 'id' enviado
        $resultados = ResultadoEscaneo::where('escaneo_id', $id)->get();
       
        return view('resultado.card', [
            'resultados' => $resultados
        ]);
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Escaneo $escaneo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Escaneo $escaneo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Escaneo $escaneo)
    {
        //
    }
}
