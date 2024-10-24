<?php
namespace App\Algoritmos\escaners;
use Illuminate\Support\Facades\Http;

use Exception;
class SucuriEscaner
{
    function apiSucuri($url)
    {
        // URL base para la API de Sucuri
        $api = 'https://sitecheck.sucuri.net/api/v3/?scan=';
        $fullUrl = $api . urlencode($url); // Asegúrate de codificar la URL
    
        try {
            // Realiza la solicitud HTTP
            $response = Http::timeout(10)->get($fullUrl);
    
            // Verifica si la solicitud fue exitosa
            if ($response->successful()) {
                // Decodifica la respuesta JSON
                $responseJson = $response->json();
                return $responseJson;
            } else {
                throw new Exception("Error en la solicitud: " . $response->status());
            }
        } catch (\Exception $e) {
            // Manejo de errores
            throw new Exception("Error al hacer la solicitud: " . $e->getMessage());
        }
    }

    function SucuriEscaner($url)
    {
        try {
            $api = $this->apiSucuri($url);
            // Puedes hacer algo con la respuesta de la API aquí
            return $api;
        } catch (Exception $e) {
            // Manejar el error de la forma que prefieras
            #echo "Se produjo un error: " . $e->getMessage();
            return null;
        }
    }
}


