<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Process\Process;

class WebScrapeController extends Controller
{
    public function scrape(Request $request) {
        $gender = $request->get('gender');
        $category = $request->get('category');
        $sort= $request->get('sort');

//        $response = Http::get('http://scraper:5000/run-scraper', [
//            'gender' => $gender,
//            'category' => $category,
//            'sort' => $sort
//        ]);
//
//        $data = json_decode($response->body(), true);
//
//        return $data;
//
        $command= [
            'docker compose',
            'exec',
            'scraper',
            'python',
            '/app/scraper.py',
            '--gender',
            $gender,
            '--category',
            $category,
            '--sort',
            $sort,
        ];

        $process = new Process($command);
        $process->run(function ($type, $buffer): void {
        if (Process::ERR === $type) {
            echo 'ERR > '.$buffer;
        } else {
            echo 'OUT > '.$buffer;
        }
        });

    }
}
