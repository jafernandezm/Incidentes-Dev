<?php

namespace App\Http\Controllers;

use App\Models\Filtracion;
use Illuminate\Http\Request;
use App\Models\Escaneo;
use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Support\Facades\Auth;
class FiltracionController extends Controller
{
    public function index()
    {
        $variables = Filtracion::all();
        //dd($variables);
        #return redirect()->route('filtrados.index');
        return view('filtracion.index');
    }

    public function show($uuid)
    {
        $datosFiltrados = Filtracion::where('escaneo_id', $uuid)->get()->toArray();
        return view('filtrados.show', compact('datosFiltrados'));
    }

    public function store(Request $request)
    {
        $contador = 0;
        $responseDataBreach = [];
        $textoNoDeseado = '<em>No se encontraron resultados para su búsqueda.';
        $errores = [];  // Array para almacenar errores
        $validated = $request->validate([
            'consulta' => 'required|string|max:255',
            'tipo' => 'required|string|in:https,correo'
        ]);

        $consulta = $validated['consulta'];
        $tipo = $validated['tipo'];

        if ($tipo === 'https') {
            try {
                $apiLeakosint = $this->callAndProcessApi($consulta);
                $responseData = $apiLeakosint['responseData'];
                $contador = $apiLeakosint['contador'];
            } catch (Exception $e) {
                // Añade el error al array de errores
                $errores[] = 'Error al procesar la API para HTTPS: ' . $e->getMessage();
            }
        } elseif ($tipo === 'correo') {
            try {
                $responseDataBreach = $this->getBreachInfo($consulta);
            } catch (Exception $e) {
                $errores[] = 'Error al obtener información de Breach: ' . $e->getMessage();
            }

            try {
                $apiLeakosint = $this->callAndProcessApi($consulta);
                $responseData = $apiLeakosint['responseData'];
                $contador = $apiLeakosint['contador'] + count($responseDataBreach['result']);
            } catch (Exception $e) {
                $errores[] = 'Error al procesar la API para Correo: ' . $e->getMessage();
            }
        };

        $escaneo = new Escaneo();
        $escaneo->url = $consulta;
        $escaneo->tipo = 'filtraciones';
        $escaneo->fecha = now();
        $escaneo->resultado = $contador;
        $escaneo->save();
        if (isset($responseData['List'])) {
            $listData = $responseData['List'];

            foreach ($listData as $api => $data) {
                $infoLeak = $data['InfoLeak'] ?? null;
                if ($infoLeak && strpos($infoLeak, $textoNoDeseado) !== false) {
                    continue; // Salta a la siguiente iteración del bucle si la información no es deseada
                }
                foreach ($data['Data'] as $item) {
                    $datos_filtrados = new Filtracion();
                    $datos_filtrados->escaneo_id = $escaneo->id;
                    $datos_filtrados->consulta = $consulta;
                    $datos_filtrados->tipo = 'filtraciones';
                    $datos_filtrados->filtracion = $api;
                    $datos_filtrados->informacion = $infoLeak;
                    $datos_filtrados->data = json_encode($item);
                    $datos_filtrados->save();
                }
            }
        }
        if (!empty($responseDataBreach['result'])) {
            $datos_filtrados = new Filtracion();
            $datos_filtrados->escaneo_id = $escaneo->id;
            $datos_filtrados->consulta = $consulta;
            $datos_filtrados->tipo = 'filtraciones';
            $datos_filtrados->filtracion = 'BreachDirectory';
            $datos_filtrados->informacion = 'Información de filtración de correo';
            $data = $responseDataBreach['result'];
            $datos_filtrados->data = json_encode($data);
            $datos_filtrados->save();
        }
        // Redirige de vuelta a la misma ruta con los errores recopilados, si los hay
        // if (!empty($errores)) {
        //     return redirect()->back()->withErrors($errores);
        // }
        $resultados = Filtracion::where('escaneo_id', $escaneo->id)->get();
        //dd($datos_filtrados);
        return view('filtracion.index', [
            'resultados' => $resultados,
            'errores' => $errores // Pasamos el array de errores manualmente
        ]);
    
    }

    private function callAndProcessApi($consulta)
    {
        // como saco el api_key de usuario para el token
        $user = Auth::user();
        $data = [
            //"token" => "7175653734:MDuicAi4",
            "token" => $user->api_key,
            "request" => $consulta,
            "limit" => 100,
            "lang" => "es"
        ];

        $url = 'https://server.leakosint.com/';

        try {
            // Aumenta el tiempo de espera a 30 segundos (30000 milisegundos)
            $response = Http::timeout(30)->asJson()->post($url, $data);
            // Verifica si la solicitud fue exitosa
            if ($response->successful()) {
                $responseData = $response->json();

                // Inicializa el contador en 0
                $contador = 0;

                // Verifica si 'List' está presente en la respuesta
                if (isset($responseData['List']) && is_array($responseData['List'])) {
                    foreach ($responseData['List'] as $key => $value) {
                        // Si la clave 'No results found' está presente en la respuesta
                        if ($key === 'No results found' && isset($value['InfoLeak'])) {
                            // Verifica si el mensaje contiene "<em>" al inicio
                            if (str_starts_with($value['InfoLeak'], '<em>No se encontraron resultados para su búsqueda')) {
                                // No hay resultados útiles
                                $contador = 0;
                            }
                        } else {
                            // Incrementa el contador si hay resultados en otras claves
                            if (isset($value['NumOfResults']) && $value['NumOfResults'] > 0) {
                                $contador += $value['NumOfResults'];
                            }
                        }
                    }
                } else {
                    // Si no hay 'List', se usa 'NumOfResults' si es mayor a 0
                    if (isset($responseData['NumOfResults']) && $responseData['NumOfResults'] > 0) {
                        $contador = $responseData['NumOfResults'];
                    }
                }

                return [
                    'responseData' => $responseData,
                    'contador' => $contador,
                ];
            } else {
                throw new Exception("Error en la solicitud: " . $response->body());
            }
        } catch (Exception $e) {
            // Maneja cualquier error que ocurra durante la solicitud
            throw new Exception("Error en la solicitud a la API: " . $e->getMessage());
        }
    }



    function getBreachInfo($correo)
    {
        // URL de la API
        $url = "https://api.breachdirectory.org/rapidapi-IscustemTaingtowItrionne?func=auto&term=" . urlencode($correo);

        // Realiza la solicitud HTTP GET
        $response = Http::get($url);

        // Verifica si la solicitud fue exitosa
        if ($response->successful()) {
            // Decodifica el JSON recibido en un array
            return $response->json();
        } else {
            // Maneja el error en caso de que la solicitud falle
            return [
                'error' => 'No se pudo recuperar la información. Código de error: ' . $response->status()
            ];
        }
    }
}
