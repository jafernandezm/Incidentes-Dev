<?php

namespace App\Algoritmos\herramientas;

class Whaweb{

    public function verificarWhatWeb($url)
    {

        $escapedUrl = escapeshellarg($url);
        $outputFile = '/var/www/whatweb/whatweb.json';
        exec("rm -f " . $outputFile);
        exec("mkdir -p /var/www/whatweb");
        $command = "/usr/bin/whatweb --log-json=" . ($outputFile) . " " . $escapedUrl;
      
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
            //dd($decodedContent);
            exec("rm -f " . $outputFile);
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
}