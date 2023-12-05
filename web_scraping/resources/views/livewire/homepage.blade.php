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

        $process = new Process($command);

        $process->start();

        foreach ($process as $type => $data) {
            if ($process::OUT === $type) {

                $jsonString = trim($data, '"""');

                $jsonString = str_replace("'", '"', $jsonString);

                $jsonString = substr($jsonString, 0, strlen($jsonString)-3);
                $jsonString = substr($jsonString, 1);

                $jsonObjects = explode("}\n{", $jsonString);

                foreach ($jsonObjects as $jsonObject) {


                    $jsonString = trim($jsonObject, '"');

                    $jsonString = str_replace('\n', "", $jsonString);

                    $jsonString = preg_replace('/"\s*:\s*"/', '":"', $jsonString);

                    $jsonString = str_replace('\"', '"', $jsonString);

                    $jsonString = str_replace('None', '"null"', $jsonString);

                    $jsonString = '{"'. $jsonString . '"}';

                    $decodedData = json_decode($jsonString, true);

                    if (json_last_error() === JSON_ERROR_NONE) {
                        $this->results[] = $decodedData;

                    } else {
                        echo 'JSON decoding error: ' . json_last_error_msg();
                    }
                };

            } else {
                echo "\nRead from stderr: ".$data;
            }
        };

        if (!$process->isSuccessful()) {
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

    </select>
    <button wire:click="scrape">Scrape</button>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
        @foreach($results as $result)
            <div class="p-10">
            <div class="rounded overflow-hidden shadow-lg">
              <img class="w-full" src="{{$result['product_image_link']}}" alt="Mountain">
              <div class="px-6 py-4">
                <div class="font-bold text-xl mb-2">{{ $result['product_name'] }}</div>
                <strong class="text-gray-700 text-base">{{ $result['product_manufacturer'] }}</strong>
                <p class="text-gray-700 text-base">
                    {{ $result['product_price'] }}
                </p>
              </div>
              <div class="px-6 pt-4 pb-2">
                <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2">{{$result['product_color_amount']}}</span>
              </div>
            </div>
          </div>
        @endforeach
    </div>
</div>
