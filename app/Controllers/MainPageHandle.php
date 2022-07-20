<?php

namespace App\Controllers;

class MainPageHandle implements Handle
{
    public function handle(): array
    {
        $url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
        $parameters = [
            'start' => '1',
            'limit' => '10',
            'convert' => 'USD'
        ];
        $headers = [
            'Accepts: application/json',
            'X-CMC_PRO_API_KEY: e077677b-5483-4605-b009-1a83ca6bb24d'
        ];

        $qs = http_build_query($parameters);
        $request = "{$url}?{$qs}"; // create the request URL

        $curl = curl_init(); // Get cURL resource

        curl_setopt_array($curl, array(
            CURLOPT_URL => $request,            // set the request URL
            CURLOPT_HTTPHEADER => $headers,     // set the headers
            CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
        ));

        $response = curl_exec($curl); // Send the request, save the response
        curl_close($curl);

        $res = [];
        if ($response) {
            foreach ((json_decode($response))->data as $data) {
                $res[] = (object)[
                    'name' => $data->name,
                    'symbol' => $data->symbol,
                    'price' => $data->quote->USD->price,
                ];
            }
        }
        return $res;
    }
}
