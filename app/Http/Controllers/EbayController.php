<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class EbayController extends Controller
{
    public function getAllProductsFromEbay()
{
    $perPage = 100; // Cantidad de productos por página
    $pageNumber = 1;
    $results = [];
    $availablePages = 0;
    
    do {
        // Realiza la solicitud a la API de eBay con la palabra clave "productos" y el filtro de ubicación
        $response = $this->makeEbayApiRequest($perPage, $pageNumber);

        // Procesa los resultados y agrégales a la matriz de resultados
        $data = json_decode($response->getBody(), true);

        if (isset($data['findItemsAdvancedResponse'][0]['searchResult'][0])) {
            $items = $data['findItemsAdvancedResponse'][0]['searchResult'][0];

            // Agrega los productos de la página actual a la matriz de resultados
            $results = array_merge($results, $items);

            // Actualiza el número total de páginas disponibles
            $availablePages = $data['findItemsAdvancedResponse'][0]['paginationOutput'][0]['totalPages'][0] - $pageNumber;
        }

        // Incrementa el número de página para la siguiente solicitud
        $pageNumber++;

    } while ($availablePages > 0);

    // Renderiza la vista 'products' y pasa los resultados como datos
    return view('products', ['products' => $results]);
}




    private function makeEbayApiRequest($perPage, $pageNumber)
    {
        $client = new Client();

        $url = 'https://svcs.ebay.com/services/search/FindingService/v1'; 
        $response = $client->get($url, [
            'query' => [
                'OPERATION-NAME' => 'findItemsAdvanced',
                'SERVICE-VERSION' => '1.0.0',
                'SECURITY-APPNAME' => env('APP_ID'),
                'RESPONSE-DATA-FORMAT' => 'JSON',
                'REST-PAYLOAD' => true,
                'paginationInput.pageNumber' => $pageNumber,
                'paginationInput.entriesPerPage' => $perPage,
                'keywords' => 'productos', 
                'itemFilter(0).name' => 'LocatedIn',
                'itemFilter(0).value' => 'ES', 
            ]
        ]);

        return $response;
    }
}
