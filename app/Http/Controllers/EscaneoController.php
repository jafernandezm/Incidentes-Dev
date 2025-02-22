<?php

namespace App\Http\Controllers;

use App\Models\Escaneo;
use Illuminate\Http\Request;
use App\Models\Filtracion;
//resultado
use App\Models\ResultadoEscaneo;
use Dompdf\Dompdf;

class EscaneoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //dd(auth()->user()->roles[0]->name);
        if (auth()->user()->roles[0]->name === 'admin') {
            $escaneos = Escaneo::orderBy('created_at', 'desc')->get();
        } else {
            $escaneos = Escaneo::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
        }

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
            // dd($escaneo);
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

    public function pdf($id)
    {
        $escaneo = Escaneo::where('id', $id)->first();
        $detalles = json_decode($escaneo->detalles, true);
        $resultados = ResultadoEscaneo::where('escaneo_id', $escaneo->id)->get();
        $pdf = new Dompdf();
        // dd([
        //     'escaneo' => $escaneo,
        //     'detalles' => $detalles,
        //     'resultados' => $resultados
        // ]);
        if ($escaneo->resultado == 0) {
            return redirect()->route('escaneo.index')->with('error', 'No se encontraron resultados');
        }
        if ($escaneo->tipo == 'PASIVO') {

            $pdf->loadHtml(view('resultado.pdfPasivo', [
                'escaneo' => $escaneo,
                'detalles' => $detalles,
                'resultados' => $resultados
            ])->render());
        } elseif ($escaneo->tipo == 'ACTIVO') {

            $pdf->loadHtml(view('resultado.pdfActivo', [
                'escaneo' => $escaneo,
                'detalles' => $detalles,
                'resultados' => $resultados
            ])->render());
        } else {
            // Si es del tipo 'filtraciones', obtenemos los datos filtrados
            $datosFiltrados = Filtracion::where('escaneo_id', $escaneo->id)->get()->toArray();
            $pdf->loadHtml(view('resultado.pdfFiltracion', [
                'escaneo' => $escaneo,
                'datosFiltrados' => $datosFiltrados,
            ])->render());
        }
        $pdf->setPaper('A4', 'landscape');
        $pdf->render();
        return $pdf->stream();
    }


    public function show($id)
    {
        // Encuentra los resultados del escaneo basados en el 'id' enviado
        $resultados = ResultadoEscaneo::where('escaneo_id', $id)->get();
            //dd($resultados);
        // Obtener el primer elemento de la colección
        if($resultados->isEmpty()){
            return redirect()->route('escaneo.index')->with('error', 'No se encontraron resultados');
        }

        $primero = $resultados->first();
        //dd($primero->detalle);
        if ($primero->detalle !== 'Ataque SEO Japones') {
            return view('resultado.card', [
                'resultados' => $resultados
            ]);
        }
        // Acceder al campo 'data'
        $data = $primero->data;

        //ahora convertir en json
        $data = json_decode($data, true);
        // Inspeccionar el contenido
        /// Inicializar un array para almacenar las URLs
        $urls = [];

        // Recorrer el JSON y extraer las URLs
        foreach ($data as $key => $value) {
            // Verificar que 'URL_ORIGEN' exista en el valor
            if (isset($value['URL_ORIGEN'])) {
                $urls[] = $value['URL_ORIGEN']; // Guardar la URL en el array
            }
        }
        // Obtener las URLs únicas
        $uniqueUrls = array_unique($urls);


   
        return view('resultado.card', [
            'resultados' => $resultados,
            'urls' => $uniqueUrls
        ]);
    }

    public function reportarEstado(Request $request)
    {
        // Validar la entrada
        // $request->validate([
        //     'estado' => 'required|string|in:reportado,no reportado,en proceso', // Validar que el estado sea uno de los valores permitidos
        //    // 'escaneo_id' => 'required|uuid|exists:escaneos,id', // Validar que escaneo_id sea un UUID existente
        // ]);

        $id = $request->escaneo_id;

        // Buscar el escaneo por ID
        $escaneo = Escaneo::findOrFail($id);
        //dd($escaneo);
        // Verificar si el estado es diferente al actual
        if ($escaneo->estado !== $request->estado) {
            // Actualizar el estado
            $escaneo->estado = $request->estado;
            $escaneo->save();

            // Redireccionar o devolver respuesta con éxito
            return redirect()->back()->with('success', 'Estado actualizado correctamente.');
        }

        // Si el estado no ha cambiado, redirigir con un mensaje de error
        return redirect()->back()->with('error', 'No hubo cambios en el estado.');
    }
}
