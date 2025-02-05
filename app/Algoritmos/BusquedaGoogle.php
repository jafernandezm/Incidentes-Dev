<?php


// app/Algoritmos/AtaqueSeoHelper.php
namespace App\Algoritmos;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
//models
use App\Models\Incidente;
class BusquedaGoogle
{
    //como creo una variable global var proxy=['https://thingproxy.freeboard.io/fetch/']
    public $proxy = [
        'https://cors-anywhere.herokuapp.com/'
    ];
    public $Header = [
        'Host' => 'www.google.com',
        'Cookie' => 'NID=521=DVG7XQYQJ8RvgaJKzulCBhbMxJSAVU4vEBgFhS7jT8daAweEoQsH95ev7ktbcT5WzbCT8sV8mVcBwY7ETVRkoMkRjGmKFPfuLimbntTK7tUTJu2my72BK3wujk9Cprn5DgVWngOZ-AcT4cmRT1NpR8RCs_FkHKvm6kISosde4pQLN3ruUQnxDBdEskT62Bg4c2b3jrERG3DT9WmmJBoYKeZhdPMfxLg0NvmzhlSCU-k6r1aCZN6VXy2kkl0AJhWd6lxXoHYU; AEC=AVcja2cWuzdbuozdixFved3Evg2tPlb5HQSXEV-m5JClF7BPuNCh8jWNuA; GCL_AW_P=GCL.1738416511.EAIaIQobChMIv43Wj8qiiwMV7l9IAB02MAuNEAAYASAAEgJPuPD_BwE',
        'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:128.0) Gecko/20100101 Firefox/128.0',
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/png,image/svg+xml,*/*;q=0.8',
        'Accept-Language' => 'en-US,en;q=0.5',
        'Accept-Encoding' => 'gzip, deflate, br',
        'Upgrade-Insecure-Requests' => '1',
        'Sec-Fetch-Dest' => 'document',
        'Sec-Fetch-Mode' => 'navigate',
        'Sec-Fetch-Site' => 'none',
        'Sec-Fetch-User' => '?1',
        'Priority' => 'u=0, i',
        'Te' => 'trailers',
    ];

    public function  googleSearch($queries = [], $timeout = 20, $numResults = 10) {
        $options = [
            RequestOptions::VERIFY => false,
            RequestOptions::TIMEOUT => $timeout,
            
        ];
        $client = new Client($options);
        $results = [];
        foreach ($queries as $query) {
            $query = str_replace(' ', '%20', $query);
            #$url = $this->proxy[0] . 'https://www.google.com/search?q=' . $query . '&num=' . $numResults;

            $url = 'https://www.google.com/search?q=' . $query . '&num=' . $numResults;
            
            try {
                $response = $client->request('GET', $url, [
                    // 'headers' => [
                    //     //'origin' => 'x-requested-with',
                    // ],
                    'headers' => $this->Header,
                ]);
               
                $content = $response->getBody()->getContents();
               
                $dom = new \DOMDocument();
                @$dom->loadHTML($content);
       
                $links = $dom->getElementsByTagName('a');
              
                foreach ($links as $link) {
                    $href = $link->getAttribute('href');
                    if (strpos($href, '/url?q=') === 0) {
                        $href = substr($href, strlen('/url?q='));
                        $href = urldecode($href);
                        $href = preg_replace('/&sa=.*$/i', '', $href);
                        if (strpos($href, 'google.com') !== false || preg_match('/\.(pdf|img)$/i', $href)) {
                            continue;
                        }
                        $titleElement = $link->getElementsByTagName('div')->item(0);
                        $title = $titleElement ? $titleElement->nodeValue : '';
                        $relatedDataElement = $link->parentNode->parentNode->getElementsByTagName('div')->item(1);
                        $relatedData = $relatedDataElement ? $relatedDataElement->nodeValue : '';
                        $results[] = [
                            'query' => $query,
                            'url' => $href,
                            'titulo' => $title,
                            'related_data' => $relatedData,
                        ];
                    }
                }
            } catch (Exception $e) {
                // Log the error instead of echoing it to avoid disrupting the flow
                error_log('Request failed: ' . $e->getMessage());
            }
        }
        dd($results);
        return $results;
    }
}
