<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Plate;
use App\Transformers\PlateTransformer;
use Illuminate\View\View;

class PlatesController extends Controller
{
    public function show(string $number): View
    {
        $plateModel = Plate::where('number', $number)
            ->with([
                'markers',
                'markers.markerMedia',
                'markers.markerComments',
            ])
            ->firstOrFail();

        $markersCoords = $plateModel->markers->map(fn ($marker) => [
            'lat' => $marker->lat,
            'lng' => $marker->lng,
            'type' => $marker->type,
        ])->toArray();

        return view('front.plates.show', [
            'plate' => fractal($plateModel, new PlateTransformer)->parseIncludes('markers')->toArray(),
            'markersCoords' => $markersCoords,
        ]);
    }
}
