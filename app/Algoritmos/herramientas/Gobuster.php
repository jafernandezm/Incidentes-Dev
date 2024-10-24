<?php

namespace App\Algoritmos\herramientas;

class Gobuster{
    public function procesarWpIncludes(array $wpIncludes, string $urlFinal)
    {
        $urlBase = rtrim($urlFinal, '/');
        $jsFileUrls = [];

        foreach ($wpIncludes as $jsFile) {
            $jsFileUrls[] = $urlBase . '/' . ltrim($jsFile, '/');
        }
        return $jsFileUrls;
    }
    public function verificarGobuster($url)
    {
        #dd($url);
        $escapedUrl = $url;
        $outputFile = "/var/www/whatweb/fuzzing.txt";
        $command = "gobuster dir -u " . $escapedUrl . " -w /var/www/fuzzing/wordpress.fuzz.txt -t 50 -o " . $outputFile;
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
}