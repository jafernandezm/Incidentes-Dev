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

//composer require guzzlehttp/promises
use App\Algoritmos\BusquedaGoogle;

class AtaqueSeoJapones
{

    private function buscarHtmlInfectado($html, $urlResponse, $url)
    {
        // Obtener HTML infectado
        $htmlInfectados = Incidente::where('tipo_id', 2)->pluck('contenido')->toArray();
        $urlOrigen = $this->normalizeDomain($url);
        $resultados = [];
        foreach ($htmlInfectados as $htmlInfectado) {
            $htmlInfectadoPattern = $htmlInfectado;
            if (strpos($htmlInfectadoPattern, '<') !== 0) {
                $htmlInfectadoPattern = '<' . $htmlInfectadoPattern;
            }
            $pattern = "/(" . preg_quote($htmlInfectadoPattern, '/') . ".*?(>|\/>))/si";
            preg_match_all($pattern, $html, $matches);
            if (!empty($matches[0])) {
                $resultados[] = [
                    'URL_ORIGEN' => $urlOrigen,
                    'URL infectada' => $urlResponse,
                    'tipo' => 'HTML infectado',
                    'html_infectado' => $htmlInfectado,
                    'html' => array_unique($matches[0])
                ];
            }
        }
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
        //dd($urls);
        $busquedaGoogle = new BusquedaGoogle();
        $client = new Client([
            RequestOptions::VERIFY => false,
            RequestOptions::TIMEOUT => 15,
            RequestOptions::ALLOW_REDIRECTS => ['track_redirects' => true],
        ]);

        $promises = [];
        foreach ($urls as $url) {
            $promises[$url['url']] = $client->getAsync($url['url'], [
                'headers' => $busquedaGoogle->Header
            ]);
        }

        // Esperar y procesar respuestas
        $responses = Promise\Utils::settle($promises)->wait();
        $results = [];
        //dd($responses);
        foreach ($responses as $url => $response) {
            if ($response['state'] === 'fulfilled') {
                //dd($url);
                $responseContent = $response['value'];
                $html = $responseContent->getBody()->getContents();
                $redirects = $responseContent->getHeader('X-Guzzle-Redirect-History');
                $urlResponse = $redirects[0] ?? $url;
                // Procesar redirecciones
                $results = array_merge($results, $this->procesarRedirecciones($url, $redirects, $urlResponse));
                // Buscar URLs infectadas y HTML infectado
                $results = array_merge($results, $this->extraUrlsScan($html, $url));
                $results = array_merge($results, $this->buscarHtmlInfectado($html, $urlResponse, $url));
            }
        }



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
        return $results;
    }
}
