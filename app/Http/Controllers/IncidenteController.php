<?php

namespace App\Http\Controllers;

use App\Models\Incidente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Tipo;

class IncidenteController extends Controller
{
    public function index()
    {
        $incidentes = Incidente::all();
        $incidentes->load('tipo');
        //dd($incidentes);
        return view('incidente.index', [
            'incidentes' => $incidentes,
        
        ]);
    }

    public function create()
    {
        $tipos = Tipo::all(); // Obtener todos los tipos de incidente desde la base de datos
        return view('incidente.create', compact('tipos'));
    }

    public function store(Request $request)
    {
        $input = $request->all();
        // Obtener la fecha actual en formato 'Y-m-d'
        $fecha = now()->toDateString();

        // Crear el incidente
        $incidente = Incidente::create([
            'tipo_id' => $input['tipo_id'],
            'contenido' => $input['contenido'],
            'descripcion' => $input['descripcion'],
            'fecha' => $fecha,
        ]);

        return redirect()->route('incidente.index');
    }

    public function show(Incidente $incidente)
    {
    
        // //tipos load
        // $incidente->load('tipo');
        return view('incidente.show', ['incidente' => $incidente]);
    }

    public function edit(Incidente $incidente)

    {
        $tipos = Tipo::all(); // Obtener todos los tipos de incidente desde la base de datos
        return view('incidente.edit', ['incidente' => $incidente,
        'tipos' => $tipos]);
    }

    public function update(Request $request, Incidente $incidente)
    {
        $incidente->update($request->all());

        return redirect()->route('incidente.index');
    }
    public function destroy(Incidente $incidente)
    {
        $incidente->delete();

        return redirect()->route('incidente.index');
    }
}
