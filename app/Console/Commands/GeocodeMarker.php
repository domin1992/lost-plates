<?php

namespace App\Console\Commands;

use App\Libraries\GoogleGeocoding;
use App\Models\Marker;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class GeocodeMarker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:geocode-marker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assignes address to marker based on lat and lng';

    /**
     * Execute the console command.
     */
    public function handle(GoogleGeocoding $googleGeocoding): void
    {
        $markers = Marker::where('formatted_address', null)->take(10)->inRandomOrder()->get();

        $markers->each(function ($marker) use ($googleGeocoding) {
            $results = $googleGeocoding->geocodeByLatLng($marker->lat, $marker->lng);

            if (Arr::get($results, 'status') !== GoogleGeocoding::STATUS_OK) {
                return;
            }

            $data = collect(Arr::get($results, 'results'))
                ->filter(fn ($resultItem) => in_array('route', Arr::get($resultItem, 'types')))
                ->map(fn ($resultItem) => [
                    'formatted_address' => Arr::get($resultItem, 'formatted_address'),
                    'place_id' => Arr::get($resultItem, 'place_id'),
                ])
                ->first();

            if (empty($data)) {
                $data = collect(Arr::get($results, 'results'))
                    ->filter(fn ($resultItem) => in_array('plus_code', Arr::get($resultItem, 'types')))
                    ->map(fn ($resultItem) => [
                        'formatted_address' => Arr::get($resultItem, 'formatted_address'),
                        'place_id' => Arr::get($resultItem, 'place_id'),
                    ])
                    ->first();
            }

            $marker->update([
                'formatted_address' => Arr::get($data, 'formatted_address'),
                'place_id' => Arr::get($data, 'place_id'),
            ]);
        });
    }
}
