<?php


use Illuminate\Support\Facades\Http;
use function Livewire\Volt\{state};

state([
    'url' => '',
    'result' => '',
]);

$scrape = function() {
    $query = "url={$this->url}";
    dd(Http::get("/scrape", $query));
    $response = Http::get("/scrape", $query);

    $this->result = $response->body();
}

?>

<div>
    <input wire:model="url" type="text" placeholder="Enter URL">
    <button wire:click="scrape">Scrape</button>

    <!-- Display the result if needed -->
    <div>
        {{ $result }}
    </div>
</div>
