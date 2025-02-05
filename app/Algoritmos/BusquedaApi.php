<?php
namespace App\Algoritmos;
use Illuminate\Support\Facades\Http;
class BusquedaApi
{
    private $apiKey = 'be5bc01daadc76d58f55964c85c864c71e96f1ccbcd1b17d5d14b2bca2972a23';

    public function googleSearch($queries = [], $numResults = 100)
    {
        $results = [];
        
        // Lista de dominios no sospechosos
        $safeDomains = [
            'google.com',
            'www.ctic.gob.bo',
            'cgii.gob.bo',
            // Puedes agregar más dominios si lo necesitas
        ];
    
        // Lista de URLs exactas a excluir
        $excludedUrls = [
            'https://sare.naabol.gob.bo/ifis-appv3/public/notam?page=7202',  // URL exacta a excluir
            // Puedes agregar más URLs exactas aquí
        ];
    
        foreach ($queries as $query) {
            // Formatear la URL para la consulta
            $url = "https://serpapi.com/search.json?engine=google&q=" . urlencode($query) . 
                   "&google_domain=google.com&gl=us&hl=en&num=" . $numResults . 
                   "&api_key=" . $this->apiKey;
    
            try {
                // Realiza la solicitud HTTP
                $response = Http::get($url);
                $data = $response->json();
    
                // Verificar si hay resultados orgánicos
                if (!empty($data['organic_results'])) {
                    foreach ($data['organic_results'] as $result) {
                        $link = $result['link'] ?? '';
    
                        // Filtrar URLs no deseadas
                        if (!$this->isSafeLink($link, $safeDomains, $excludedUrls)) {
                            $results[] = [
                                'query' => $query,
                                'url' => $link,
                                'titulo' => $result['title'] ?? '',
                                'related_data' => $result['snippet'] ?? '',
                                'position' => $result['position'] ?? null,
                                'displayed_link' => $result['displayed_link'] ?? '',
                            ];
                        }
                    }
                }
    
                // Procesar otros resultados como búsquedas relacionadas
                if (!empty($data['related_searches'])) {
                    foreach ($data['related_searches'] as $related) {
                        $link = $related['link'] ?? '';
                        if (!$this->isSafeLink($link, $safeDomains, $excludedUrls)) {
                            $results[] = [
                                'query' => $query,
                                'related_search' => $related['query'] ?? '',
                                'url' => $link,
                            ];
                        }
                    }
                }
    
            } catch (Exception $e) {
                // Manejo de excepciones
                error_log('Error en la búsqueda: ' . $e->getMessage());
            }
        }
    
        //dd($results);  // Para ver la salida de los resultados
        return $results;
    }
    
    /**
     * Verifica si la URL está en la lista de dominios no sospechosos o en la lista de URLs excluidas
     */
    private function isSafeLink($link, $safeDomains, $excludedUrls)
    {
        // Parseamos la URL para comparar solo la parte base
        $parsedLink = parse_url($link);
        $parsedExcludedUrls = array_map(function($url) {
            return parse_url($url, PHP_URL_HOST) . parse_url($url, PHP_URL_PATH);
        }, $excludedUrls);

        // Verificar si el enlace es un dominio no sospechoso
        foreach ($safeDomains as $safeDomain) {
            if (strpos($link, $safeDomain) !== false) {
                return true; // La URL está en la lista de dominios no sospechosos
            }
        }

        // Verificar si la URL está en la lista de URLs excluidas
        $linkBase = $parsedLink['host'] . $parsedLink['path'];
        foreach ($parsedExcludedUrls as $excludedUrl) {
            if ($linkBase === $excludedUrl) {
                return true; // La URL es exactamente la que se quiere excluir
            }
        }

        return false; // La URL no está en la lista, es sospechosa
    }

    
    

    public function googleEmail($queries = [], $numResults = 10)
    {
        $results = [];
    
        foreach ($queries as $query) {
            $url = "https://serpapi.com/search.json?engine=google&q=" . urlencode($query) . 
                   "&google_domain=google.com&gl=us&hl=en&num=" . $numResults . 
                   "&api_key=" . $this->apiKey;
    
            try {
                $response = Http::timeout(60)  // Establece un tiempo de espera de 60 segundos
                                ->get($url);  // Realiza la solicitud GET
                $data = $response->json();
    
                // Solo agregamos la consulta y la cantidad de resultados
                $results[] = [
                    'query' => $query,
                    'num_results' => $numResults,
                ];
    
            } catch (Exception $e) {
                error_log('Error en la búsqueda: ' . $e->getMessage());
            }
        }
    
        dd($results);  // Para ver la salida de los resultados en la depuración
        return $results;
    }
    
}