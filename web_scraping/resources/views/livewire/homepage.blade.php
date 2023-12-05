<?php
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\On;
use function Livewire\Volt\{state};
use function Livewire\Volt\{mount};
use Symfony\Component\Process\Process;

state([
    'url' => '',
    'gender' => 'heren',
    'category' => 'schoenen',
    'sort'=> 'populairste',
    'results' => [],
]);

$scrape = function() {

        $this->results = [];
        // Build the command
        $command = [
            'python3',
            '/app/PythonScripts/scraper.py',
            '--gender',
            $this->gender,
            '--category',
            $this->category,
            '--sort',
            $this->sort
        ];

        // Create a new Process
        $process = new Process($command);

        $process->start();

        foreach ($process as $type => $data) {
            if ($process::OUT === $type) {

                // Remove leading and trailing triple double quotes
                $jsonString = trim($data, '"""');

                // Replace single quotes with double quotes
                $jsonString = str_replace("'", '"', $jsonString);

                $jsonString = substr($jsonString, 0, strlen($jsonString)-3);
                $jsonString = substr($jsonString, 1);

                $jsonObjects = explode("}\n{", $jsonString);

                foreach ($jsonObjects as $jsonObject) {


                    // Remove leading and trailing double quotes
                    $jsonString = trim($jsonObject, '"');

                    // Replace escaped newline characters with actual newlines
                    $jsonString = str_replace('\n', "", $jsonString);

                    // Remove whitespace between keys and values
                    $jsonString = preg_replace('/"\s*:\s*"/', '":"', $jsonString);

                    // Replace escaped double quotes with actual double quotes
                    $jsonString = str_replace('\"', '"', $jsonString);

                    $jsonString = str_replace('None', '"null"', $jsonString);

                    $jsonString = '{"'. $jsonString . '"}';

                    // dd($jsonString);

                    // Decode the JSON-like string into a PHP associative array
                    $decodedData = json_decode($jsonString, true);

                    if (json_last_error() === JSON_ERROR_NONE) {
                        // Add the decoded data to the results array
                        $this->results[] = $decodedData;

                    } else {
                        // Handle decoding error
                        echo 'JSON decoding error: ' . json_last_error_msg();
                    }
                };

            } else { // $process::ERR === $type
                echo "\nRead from stderr: ".$data;
            }
        };

        if (!$process->isSuccessful()) {
            // Handle process failure
        };
};

mount(function() use ($scrape) {
    $this->scrape();
}
);
?>

<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <select wire:model="gender">
        <option value="heren">Heren</option>
        <option value="dames">Dames</option>
        <option value="jongens">Jongens</option>
        <option value="meisjes">Meisjes</option>
    </select>
    <select wire:model="category">
        <option value="schoenen">Schoenen</option>
        <option value="tassen">Tassen</option>
        <option value="accessoires">Accessoires</option>
        <!-- Add other category options as needed -->
    </select>
    <select wire:model="sort">
        <option value="populairste">Populairste</option>
        <option value="nieuwste">Nieuwste</option>
        <option value="prijs-laag-hoog">Prijs (laag - hoog)</option>
        <option value="prijs-hoog-laag">Prijs (hoog - laag)</option>

        <!-- Add other sort options as needed -->
    </select>
    <button wire:click="scrape">Scrape</button>

    <!-- Display the results in tiles using tailwindcss styling -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
        @foreach($results as $result)
            <div class="border p-4 rounded shadow text-white">
                <!-- Display individual result data here -->
                <img src="{{$result['product_image_link']}}">
                <p class="">{{ $result['product_manufacturer'] }}</p>
                <p>{{ $result['product_name'] }}</p>
                <p>{{ $result['product_price'] }}</p>
                <!-- Add more fields as needed -->
            </div>
        @endforeach
    </div>
</div>
