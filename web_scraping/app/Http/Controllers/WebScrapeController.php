<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class WebScrapeController extends Controller
{
    public function scrape(Request $request) {
        $gender = $request->get('gender');
        $category = $request->get('category');
        $sort= $request->get('sort');

        //$results = [];

//        foreach ($categories as $category) {
//            foreach ($priceRanges as $priceRange) {
//                $process = new \Symfony\Component\Process\Process([
//                    'python', 'path/to/your/scraper.py', '--category=' . $category, '--price_range=' . $priceRange,
//                ]);
//
//                $process->run();
//
//                // Collect the results
//                $results[] = $process->getOutput();
//            }
//        }
//

       // // Collect the results
       // $results[] = $process->getOutput();


       // return response()->json($results);
       // }
       // //

        $process = new Process([
            //'python', 'path/to/your/scraper.py', '--category=' . $category, '--price_range=' . $priceRange,
            'docker-compose exec scraper python /app/scraper.py --gender ' . $gender . ' --category ' . $category . ' --sort ' . $sort,
        ]);

        // Option 1, using start

//        $process->start();
//
//        foreach ($process as $type => $data) {
//            if ($process::OUT === $type) {
//                echo "\nRead from stdout: " . $data;
//                # code...
//            } else {
//                echo "\nRead from stderr: " . $data;
//            }
//            # code...
//
        // Option 2, using run


        $process->run(function ($type, $buffer): void {
        if (Process::ERR === $type) {
            echo 'ERR > '.$buffer;
        } else {
            echo 'OUT > '.$buffer;
        }
        });

    }
}
