<?php

namespace App\Algoritmos;

use GuzzleHttp\Client;

//models
use App\Models\Incidente;
use Illuminate\Support\Facades\Log;

class AtaqueNDSW
{
    function verificarWhatWeb($url)
    {
        $escapedUrl = escapeshellarg($url);
        $outputFile = '/var/www/whatweb/whatweb.json';
        $command = "/usr/bin/whatweb --log-json=" . escapeshellarg($outputFile) . " " . $escapedUrl;
        $output = [];
        $returnVar = 0;
        exec($command . " 2>&1", $output, $returnVar);
        if ($returnVar !== 0) {
            return ['error' => 'Error al ejecutar el comando', 'output' => implode("\n", $output)];
        }
        if (file_exists($outputFile)) {
            $jsonContent = file_get_contents($outputFile);
            $jsonContent = trim($jsonContent);
            $decodedContent = json_decode($jsonContent, true);
            exec("rm -f " . escapeshellarg($outputFile));
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decodedContent;
            } else {
                return [
                    'error' => 'Error al decodificar el JSON',
                    'json_error' => json_last_error_msg(),
                    'json_content' => $jsonContent
                ];
            }
        } else {
            return ['error' => 'Archivo no encontrado'];
        }
    }

    function verificarWordpressSitecheck($url)
    {
        $api = 'https://sitecheck.sucuri.net/api/v3/?scan=';
        $response = file_get_contents($api . $url);
        $data = json_decode($response);
        if ($data === null) {
            return 'Error al decodificar la respuesta';
        }
        if (isset($data->software->cms[0]->name) && $data->software->cms[0]->name === 'WordPress') {
            $respuesta = [
                'wp-includes' => $data->links->js_local,
                'url_final' => $data->site->final_url,
            ];

            return $respuesta;
        } else {
            return [];
        }
    }

    public function sacarPlugins($url)
    {
        $escapedUrl = escapeshellarg($url);
        $outputFile = '/var/www/whatweb/plugins.json';
        $command = "/usr/bin/whatweb --log-json-verbose=" . escapeshellarg($outputFile) . " " . $escapedUrl;
        $output = [];
        $returnVar = 0;
        exec($command . " 2>&1", $output, $returnVar);
        if ($returnVar !== 0) {
            return ['error' => 'Error al ejecutar el comando', 'output' => implode("\n", $output)];
        }
        if (file_exists($outputFile)) {
            $jsonContent = file_get_contents($outputFile);
            $jsonContent = trim($jsonContent);

            $patternQuotes = '/["\'](https?:\/\/[^"\']*\.js(?:\?[^"\']*)?)["\']/';
            // Expresión regular para URLs en comillas
            $patternQuotes = '/["\'](https?:\/\/[^"\']*\.js(?:\?[^"\']*)?)["\']/';
            preg_match_all($patternQuotes, $jsonContent, $matchesQuotes);
            $jsUrlsQuotes = $matchesQuotes[1];

            // Expresión regular para URLs en el atributo src
            $patternSrc = '/src=["\']?(https?:\/\/[^"\'>\s]+\.js)(?=["\']?\s|>)/i';
            preg_match_all($patternSrc, $jsonContent, $matchesSrc);
            $jsUrlsSrc = $matchesSrc[1];

            // Combinar URLs únicas
            $allJsUrls = array_unique(array_merge($jsUrlsQuotes, $jsUrlsSrc));

            $cleanJsUrls = array_map(function ($url) {
                return rtrim($url, '\"');
            }, $allJsUrls);
            //dd($cleanJsUrls);
            exec("rm -f " . escapeshellarg($outputFile));
            if (json_last_error() === JSON_ERROR_NONE) {
                return $cleanJsUrls;
            } else {
                return [
                    'error' => 'Error al decodificar el JSON',
                    'json_error' => json_last_error_msg(),
                    'json_content' => $jsonContent
                ];
            }
        } else {
            return ['error' => 'Archivo no encontrado'];
        }
    }

    protected function procesarWpIncludes(array $wpIncludes, string $urlFinal)
    {
        $urlBase = rtrim($urlFinal, '/');
        $jsFileUrls = [];

        foreach ($wpIncludes as $jsFile) {
            $jsFileUrls[] = $urlBase . '/' . ltrim($jsFile, '/');
        }
        return $jsFileUrls;
    }

    public function ataqueNDSW($url)
    {
        $results = [];
        $jsFileUrls = [];
        $plugins = [];
        $respuesta = $this->verificarWordpressSitecheck($url);
        if (!empty($respuesta['wp-includes'])) {
            $jsFileUrls = $this->procesarWpIncludes($respuesta['wp-includes'], $respuesta['url_final']);
        }
        $respuestaWhaweb = $this->verificarWhatWeb($url);
        if (!empty($respuestaWhaweb)) {
            // Verificar si "WordPress" está en el primer y segundo elemento
            $isWordPressInFirst = isset($respuestaWhaweb[0]['plugins']['WordPress']);
            $isWordPressInSecond = isset($respuestaWhaweb[1]['plugins']['WordPress']);
            if ($isWordPressInFirst || $isWordPressInSecond) {
                $plugins = $this->sacarPlugins($url);

                //$gobusterResults = $this->verificarGobuster($url);
                //dd($gobusterResults);

            }
        }
        $gobusterResults = $this->verificarGobuster($url);

        // Combinar los arrays y obtener los elementos únicos
        $allItems = array_merge($jsFileUrls, $plugins);
        $uniqueItems = array_unique($allItems);
        //unimos con gobusterResults
        //dd($uniqueItems);
        $uniqueItems = array_merge($uniqueItems, $gobusterResults);
        //dd($uniqueItems);
        //dd($uniqueItems);
        foreach ($uniqueItems as $jsFile) {
            //verificar cuando llegue aqui
            // if ( $jsFile == 'http://192.168.24.35:5000/wp-includes/js/admin-bar1.js') {
                
            //     $jsResults = $this->verificarArchivoJs($jsFile);
            // }
            $jsResults = $this->verificarArchivoJs($jsFile);
            if (!empty($jsResults)) {
                $results[] =
                    [
                        'tipo' => 'Ataque NDSW',
                        'url' => $url,
                        'url_js' => $jsFile,
                        'contenido' => $jsResults
                    ];
            }
        }
        //dd($results);
        return $results;
    }

    function verificarGobuster($url)
    {
        $baseUrl = "http://";
        $escapedUrl = $baseUrl . $url;
        $outputFile = "/var/www/whatweb/fuzzing.txt";
        $command = "gobuster dir -u " . $escapedUrl . " -w /var/www/fuzzing/wordpress.fuzz.txt -o " . $outputFile;
        $output = [];
        $returnVar = 0;
        exec($command . " 2>&1", $output, $returnVar);
        exec("cat " . $outputFile . " | grep .js |  awk '{print $1}' > /var/www/whatweb/positivos.txt");
        $outputFilePositivos = "/var/www/whatweb/positivos.txt";
        if ($returnVar !== 0) {
            return ['error' => 'Error al ejecutar el comando', 'output' => implode("\n", $output)];
        }
        if (file_exists($outputFile)) {
            $fileContent = file_get_contents($outputFilePositivos);
            $lines = explode("\n", trim($fileContent));
            $lines = array_filter($lines, function ($line) {
                return !empty(trim($line));
            });
            $jsFilesArray = array_values($lines);
            $jsonContent= $this->procesarWpIncludes($jsFilesArray, $escapedUrl);
            return $jsonContent;
        } else {
            return ['error' => 'Archivo no encontrado'];
        }
    }
    private function verificarArchivoJs($urlBase)
    {
        try {
            $jsContent = $this->descargarContenido($urlBase);
            $contienesNDSW = $this->contieneNDSW($jsContent);
            if (!empty($contienesNDSW)) {
                return $contienesNDSW; // Retorna la URL del archivo JS si contiene NDSW
            }
        } catch (\Exception $e) {
            Log::error("Error al acceder a: $urlBase - " . $e->getMessage());
        }
        return null;
    }
    
    private function descargarContenido($url)
    {
        $client = new Client();
        $response = $client->get($url);
        return (string) $response->getBody();
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
