<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class WebScrapeController extends Controller
{
    public function scrape(Request $request) {
        # code...
        # kiggityCode
        $url = $request->get('url');

        $client =  new Client();

        $response = $client->request(
            'GET',
            $url
        );

        $response_status_code = $response->getStatusCode();
        $response_body = $response->getBody()->getContents();

        if ($response_status_code == 200) {
            dd($response_body);
        };

        dd($response_status_code);
    }
    //
}
