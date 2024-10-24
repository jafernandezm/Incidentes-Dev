<?php

namespace App\Algoritmos;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
//models
use App\Models\Incidente;
use Illuminate\Support\Facades\Log;
//herramientas
use App\Algoritmos\herramientas\Whaweb;
use App\Algoritmos\herramientas\Gobuster;
use App\Algoritmos\escaners\SitecheckSucuri;

class AtaqueNDSW
{
    public $tecnologias=[
        'WordPress',
        'Joomla',
        'Drupal',
        'Magento',
        'PrestaShop',
        'Shopify',
        'WooCommerce',
        'OpenCart',
        'Laravel',
        'Symfony',
        'CodeIgniter',
        'Yii',
        'Ruby on Rails',
        'Django',
        'Flask',
        'Express.js',
        'React',
        'Angular',
        'Vue.js',
    ];


    public function ataqueNDSW($url)
    {
    
        $results = [];
        $jsFileUrls = [];
        $plugins = [];
        $gobusterResults = []; // Inicializa la variable para evitar errores
        $whaweb = new Whaweb();
        $respuestaWhaweb = $whaweb->verificarWhatWeb($url);
      
        //dd($respuestaWhaweb);
        if (!empty($respuestaWhaweb)) {
            $isWordPressInFirst = isset($respuestaWhaweb[0]['plugins']['WordPress']);
            $isWordPressInSecond = isset($respuestaWhaweb[1]['plugins']['WordPress']);
            //dd($isWordPressInFirst);
            //dd($isWordPressInSecond);
            if ($isWordPressInFirst || $isWordPressInSecond) {
                #$plugins = $whaweb->sacarPlugins($url);
                $gobuster = new Gobuster();
                $gobusterResults = $gobuster->verificarGobuster($url);
                //dd($gobusterResults);
            }
        }
        $sucuriEscaner = new SitecheckSucuri();
        $respuesta = $sucuriEscaner->apiSucuri($url);

        if (!empty($respuesta['wp-includes'])) {
            $jsFileUrls = $this->procesarWpIncludes($respuesta['wp-includes'], $respuesta['url_final']);
        }
        $allItems = array_merge($jsFileUrls, $plugins);
        if (!empty($gobusterResults)) {
            $allItems = array_merge($allItems, $gobusterResults);
        }
        $uniqueItems = array_unique($allItems);
        foreach ($uniqueItems as $jsFile) {
            $jsResults = $this->verificarArchivoJs($jsFile);
            if (!empty($jsResults)) {
                $results[] = [
                    'tipo' => 'Ataque NDSW',
                    'url' => $url,
                    'url_js' => $jsFile,
                    'contenido' => $jsResults
                ];
            }
        }
        //dd($respuestaWhaweb);
        $pluginsCms = $this->obtenerPluginsCms($respuestaWhaweb);
        if ( count($results) > 0) {
            return [
                'results' => $results,
                'data' => [
                    'IP' => $respuestaWhaweb[0]['plugins']['IP']['string'][0] ?? '',
                    'HTTPServer' => $respuestaWhaweb[0]['plugins']['HTTPServer']['string'][0] ?? '',
                    'http_status' => $respuestaWhaweb[0]['http_status'] ?? '',
    
                    'CMS' => $pluginsCms,  // Incluye los plugins de todos los CMS aquí['WordPress'] ?? '',
                    'request_config' => $respuestaWhaweb[0]['request_config'] ?? [],
                    'cantidad' => count($uniqueItems) ?? 0,
                    'cantidadIncidentes' => count($results) ?? 0
                ]
            ];
        }
       return [];
    }
    public function obtenerPluginsCms($respuestaWhaweb) {
        $plugins = $respuestaWhaweb[0]['plugins'] ?? [];
        $tecnologiasEncontradas = [];
    
        // Recorrer los plugins y verificar si alguna tecnología está presente
        foreach ($this->tecnologias as $tecnologia) {
            $version = isset($plugins[$tecnologia]['version'])
            ? (is_array($plugins[$tecnologia]['version']) ? implode(', ', $plugins[$tecnologia]['version']) : (string)$plugins[$tecnologia]['version'])
            : '';
            if (array_key_exists($tecnologia, $plugins)) {
                $tecnologiasEncontradas[] = [
                    'TEC' => $tecnologia,
                    'version' => $version
                ];
              
            }
        }
        return $tecnologiasEncontradas;
        // Unir las tecnologías encontradas en una cadena separada por comas
        //return implode(', ', $tecnologiasEncontradas);
    }
    

    function procesarWpIncludes(array $wpIncludes, string $urlFinal)
    {
        $urlBase = rtrim($urlFinal, '/');
        $jsFileUrls = [];
        foreach ($wpIncludes as $jsFile) {
            $jsFileUrls[] = $urlBase . '/' . ltrim($jsFile, '/');
        }
        return $jsFileUrls;
    }
    private function verificarArchivoJs($urlBase)
    {
        try {
            $jsContent = $this->descargarContenido($urlBase);
            $contienesNDSW = $this->contieneNDSW($jsContent);
            if (!empty($contienesNDSW)) {
                return $contienesNDSW;
            }
        } catch (\Exception $e) {
            Log::error("Error al acceder a: $urlBase - " . $e->getMessage());
        }
        return null;
    }

    private function descargarContenido($url)
    {
        $client = new Client([
            'timeout'  => 15,  // Tiempo de espera en segundos
        ]);

        try {
            $response = $client->get($url);
            return (string) $response->getBody();
        } catch (RequestException $e) {
            // Manejo de errores, si es necesario
            return 'Error en la solicitud: ' . $e->getMessage();
        }
    }

    private function contieneNDSW($content)
    {
        // Obtener los contenidos de los incidentes de tipo ataques NDSW
        $ndswContenidos = Incidente::where('tipo_id', 5)->pluck('contenido');
        foreach ($ndswContenidos as $ndswContenido) {
            $ndswContenido = str_replace('"', '', $ndswContenido);
            $pos = strpos($content, $ndswContenido);
            if ($pos !== false) {
                $start = max(0, $pos - 100); // 100 caracteres antes del patrón
                $length = min(strlen($content) - $start, 200); // 200 
                $relevantCode = substr($content, $start, $length);
                return $relevantCode; // Retorna la sección del código que contiene el patrón
            }
        }
        return null; // No contiene NDSW
    }
}
