<?php

namespace App\Algoritmos;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\MultiRequest;
use GuzzleHttp\Psr7\Request;
//models
use App\Models\Incidente;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
//composer require guzzlehttp/promises
use App\Algoritmos\BusquedaGoogle;

class AtaqueSeoJapones
{

    private function buscarHtmlInfectado($html, $urlResponse, $url)
    {
        // Obtener HTML infectado desde la base de datos
        $htmlInfectados = Incidente::where('tipo_id', 2)->pluck('contenido')->toArray();

        // Normalizar dominio de origen
        $urlOrigen = $this->normalizeDomain($url);
        $resultados = [];

        // Verificar si hay patrones de HTML infectado
        if (empty($htmlInfectados)) {
            // Registrar log si no hay patrones
            Log::warning('No se encontraron patrones de HTML infectado para verificar.');
            return $resultados; // Retornar vacío si no hay patrones
        }

        // Buscar patrones de HTML infectado
        foreach ($htmlInfectados as $htmlInfectado) {
            // Normalizar patrón
            $htmlInfectadoPattern = $htmlInfectado;
            if (strpos($htmlInfectadoPattern, '<') !== 0) {
                $htmlInfectadoPattern = '<' . $htmlInfectadoPattern;
            }

            // Construir expresión regular para buscar HTML infectado
            $pattern = "/(" . preg_quote($htmlInfectadoPattern, '/') . ".*?(>|\/>))/si";

            // Buscar coincidencias en el HTML
            preg_match_all($pattern, $html, $matches);

            if (!empty($matches[0])) {
                // Agregar resultado al array
                $resultados[] = [
                    'URL_ORIGEN' => $urlOrigen,
                    'URL infectada' => $urlResponse,
                    'tipo' => 'HTML infectado',
                    'html_infectado' => $htmlInfectado,
                    'html' => array_unique($matches[0])
                ];
            }
        }

        // Buscar URLs de archivos .js y .json en el HTML
        $patternUrls = '/(?:src|href|content)\s*=\s*["\']([^"\']+\.(?:js|json))["\']/i';
        preg_match_all($patternUrls, $html, $matchesUrls);

        // Obtener las URLs únicas
        $urlsJsJson = array_unique($matchesUrls[1]);

        // Analizar cada archivo .js o .json
        foreach ($urlsJsJson as $archivoUrl) {
            try {
                // Realizar la solicitud GET
                $response = Http::get($archivoUrl);

                if ($response->ok()) {
                    $contenido = $response->body();
                    $lineas = explode("\n", $contenido);

                    // Detectar contenido codificado en Base64 con un patrón más específico
                    $base64Pattern = '/(?:\d{2,3},\s*){19,}\d{2,3}/';
                    preg_match_all($base64Pattern, $contenido, $coincidenciasBase64);
                    $base64Detectado = array_filter($coincidenciasBase64[0], function ($match) {
                        // Validar longitud mínima para evitar falsos positivos
                        return strlen($match) >= 10;
                    });

                    // Detectar uso de String.fromCharCode
                    $fromCharCodePattern = '/String\.fromCharCode\((\d{1,3},?)+\)/';
                    preg_match_all($fromCharCodePattern, $contenido, $coincidenciasFromCharCode);

                    // Agregar resultado si contiene código sospechoso
                    if (!empty($base64Detectado) || !empty($coincidenciasFromCharCode[0])) {
                        $resultados[] = [
                            'URL_ORIGEN' => $urlOrigen,
                            'URL infectada' => $archivoUrl,
                            'tipo' => 'JS/JSON sospechoso',
                            'html_infectado' => implode("\n", array_slice($lineas, 0, 30)), // Primeras 30 líneas
                            'html' => [
                                'base64_detectado' => array_values($base64Detectado),
                                'string_fromCharCode_detectado' => array_values($coincidenciasFromCharCode[0]),
                                'primeras_lineas' => array_slice($lineas, 0, 30),
                            ]
                        ];
                    }
                } else {
                    // Manejar error de solicitud
                    Log::error("Error al obtener contenido de: $archivoUrl. HTTP " . $response->status());
                }
            } catch (\Exception $e) {
                // Manejar excepciones
                Log::error("Excepción al analizar $archivoUrl: " . $e->getMessage());
            }
        }


        //dd($resultados);
        return $resultados;
    }



    private function extraUrlsScan($html, $url)
    {
        $pattern = '/https?:\/\/[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}(?=\/|$)/i';
        preg_match_all($pattern, $html, $matches);
        $urls = array_unique($matches[0]);
        $infectedUrls = Incidente::whereIn('contenido', $urls)->get();



        return $infectedUrls->map(function ($infectedUrl) use ($url) {
            return [
                'URL_ORIGEN' => $url,
                'url' => $url,
                'url infectada' => $infectedUrl->contenido,
                'tipo' => 'url_infectada',
            ];
        })->toArray();
    }

    public function AtaqueSeoJapones($urls)
    {
        // Dominios que deseas filtrar
        $dominiosFiltrar = [
            'cgii.gob.bo',
            'agetic.gob.bo',
            'csirt.gob.bo',
            'www.csirt.gob.bo',
            'www.cgii.gob.bo',
            'www.agetic.gob.bo',
        ];

        // Filtrar las URLs
        $urls = array_filter($urls, function ($item) use ($dominiosFiltrar) {
            foreach ($dominiosFiltrar as $dominio) {
                if (strpos($item['url'], $dominio) !== false) {
                    return false; // Excluir este dominio
                }
            }
            return true; // Incluir el resto
        });

      
        $busquedaGoogle = new BusquedaGoogle();
        $client = new Client([
            RequestOptions::VERIFY => false,
            RequestOptions::TIMEOUT => 35,
            RequestOptions::ALLOW_REDIRECTS => ['track_redirects' => true],
        ]);
        //dd($urls);
        $promises = [];
        foreach ($urls as $url) {
            $promises[$url['url']] = $client->getAsync($url['url'], [
                //'headers' => $busquedaGoogle->Header
            ]);
        }

        // Esperar y procesar respuestas
        $responses = Promise\Utils::settle($promises)->wait();
        $results = [];



        foreach ($responses as $url => $response) {
            if ($response['state'] === 'fulfilled') {

                $responseContent = $response['value'];
                $html = $responseContent->getBody()->getContents();
               
                //quiero ver el contenido de la respuesta de https://prensa.ipelc.gob.bo/hax.htm
                // if ($url == "https://prensa.ipelc.gob.bo/hax.htm") {
                //     dd($html);
                // }
                $redirects = $responseContent->getHeader('X-Guzzle-Redirect-History');
                $urlResponse = $redirects[0] ?? $url;
                // Procesar redirecciones
                $results = array_merge($results, $this->procesarRedirecciones($url, $redirects, $urlResponse));
                // Buscar URLs infectadas y HTML infectado
                $results = array_merge($results, $this->extraUrlsScan($html, $url));

                $results = array_merge($results, $this->buscarHtmlInfectado($html, $urlResponse, $url));

                //dd( $results);
            }
        }

        // dd([

        //     'results' => $results
        // ]);
        //dd ($results);

        // Obtener la IP y otros datos adicionales
        // $respuestaWhatweb = $this->getWhatwebResponse($url); // Función que obtiene la IP, ajusta esto según tu implementación
        //$uniqueItems = array_unique($results); // Obtenemos ítems únicos de los resultados procesados
        // Aquí es importante asegurarse de que el resultado tenga un formato adecuado para manejar la unicidad
        $uniqueItems = count($responses); // Ajuste para calcular ítems únicos según su lógica
        //return $results;
        // Devolver 'results' y 'data' en un solo return
        if (count($results) > 0) {
            return [
                'results' => $results, // Aquí van los resultados procesados
                'data' => [
                    'IP' => "Busqueda Seo Japones", // IP obtenida
                    'cantidad' => $uniqueItems, // Cantidad de ítems únicos
                    'cantidadIncidentes' => count($results) // Cantidad total de incidentes
                ]
            ];
        }
        return [
            'data' => [
                'IP' => "Busqueda Seo Japones", // IP obtenida
                'cantidad' => $uniqueItems, // Cantidad de ítems únicos
                'cantidadIncidentes' => count($results) // Cantidad total de incidentes
            ]
        ];
    }

    private function normalizeDomain($url)
    {
        $parsedUrl = parse_url($url);
        $host = $parsedUrl['host'] ?? '';
        return preg_replace('/^www\./', '', $host);
    }

    private function procesarRedirecciones($url, $redirects, $urlResponse)
    {
        $results = [];
        $domain = $this->normalizeDomain($url);
        $uniqueRedirects = [];

        foreach ($redirects as $redirect) {
            $redirectDomain = $this->normalizeDomain($redirect);

            // Crear las diferentes versiones de la URL (http, https, con www y sin www)
            $httpUrl = "http://{$redirectDomain}";
            $httpsUrl = "https://{$redirectDomain}";
            $httpWwwUrl = "http://www.{$redirectDomain}";
            $httpsWwwUrl = "https://www.{$redirectDomain}";

            // Validar si es la misma redirección con http, https, con y sin www
            if (
                $redirectDomain !== $domain &&
                !in_array($redirect, $uniqueRedirects) &&
                $redirect !== $httpUrl &&
                $redirect !== $httpsUrl &&
                $redirect !== $httpWwwUrl &&
                $redirect !== $httpsWwwUrl
            ) {
                $uniqueRedirects[] = $redirect;
                $results[] = [
                    'URL_ORIGEN' => $url,
                    'url' => $urlResponse,
                    'tipo' => 'redireccion',
                    'redirecciones' => $redirects,
                ];
            }
        }
        //dd($results);
        return $results;
    }
}
