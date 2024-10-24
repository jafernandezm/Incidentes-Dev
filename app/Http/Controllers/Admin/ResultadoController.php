<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//resultado
use App\Models\ResultadoEscaneo;

//escaneos
use App\Models\Escaneo;
use App\Models\Filtracion;
class ResultadoController extends Controller
{
    public function index()
    {

        $escaneos = Escaneo::orderBy('created_at', 'desc')->paginate(10); // 5 items por página
        return view('index', [
            'escaneos' => $escaneos
        ]);
    }

    public function welcome()
    {
        return view('welcome');
    }

    public function enviar(Request $request)
    {

        $tipo = $request->input('tipo');
        //dd($tipo);
        if ($tipo == 'filtraciones') {
            $uuid = $request->input('uuid');

            $datosFiltrados = Filtracion::where('escaneo_id', $uuid)->get()->toArray();
            //dividir los datos segun su tipo de filtracion
            $resultados = $datosFiltrados;

            return view('filtrados.tables-filtrados', [
                'resultados' => $datosFiltrados,
            ]);
        } elseif ($tipo == 'PASIVO') {
            $uuid = $request->input('uuid');
            $resultados = ResultadoEscaneo::where('escaneo_id', $uuid)->get()->toArray();
            //dd($resultados);
            //dd($resultados);
            // Redirigir a una vista específica para resultados
            return view('pasivo.resultado', [
                'resultados' => $resultados
            ]);
        } elseif ($tipo == 'ACTIVO') {
            $uuid = $request->input('uuid');
            $resultados = ResultadoEscaneo::where('escaneo_id', $uuid)->get()->toArray();
            //dd($resultados);
            // Redirigir a una vista específica para resultados
            return view('activo.resultado', [
                'resultados' => $resultados
            ]);
        } else {
            // Redirigir a una vista por defecto si no se encuentra ninguno
            return redirect()->route('index')->with('error', 'No se encontraron resultados');
        }
    }
}
