<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Plate;
use App\Transformers\PlateTransformer;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PlatesController extends Controller
{
    public function show(string $lang, string $number): View | RedirectResponse
    {
        $plateModel = Plate::where('number', $number)
            ->with([
                'markers',
                'markers.markerMedia',
                'markers.markerComments',
            ])
            ->first();

        if (!$plateModel) {
            return redirect()->route('front.maps.index');
        }

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
