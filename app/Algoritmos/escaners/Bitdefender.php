<?php

namespace App\Algoritmos\escaners;
use Illuminate\Support\Facades\Http;

class Bitdefender {

    function apiBitdefender($url)
    {
        // link original : https://trafficlight.bitdefender.com/info/
        $linkPagina = 'https://trafficlight.bitdefender.com/info/';
        
        $api = 'https://nimbus.bitdefender.net/url/status';
        $data = ['url' => $url];
        $response = Http::post($api, $data);
        
        // Manejar la respuesta
        if ($response->successful()) {
            return $response->json();
        } else {
            // Manejar errores
            return [
                'error' => $response->status(),
                'message' => $response->body()
            ];
        }
    }

    function BitdefenderEscaner($url)
    {
        $api = $this->apiBitdefender($url);

        return $api;
    }


}