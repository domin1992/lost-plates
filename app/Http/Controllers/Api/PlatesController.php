<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plate;
use App\Transformers\PlateTransformer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;

class PlatesController extends Controller
{
    public function show(string $id): JsonResponse
    {
        return $this->showResponse(Plate::where('id', $id));
    }

    public function showByNumber(string $number): JsonResponse
    {
        return $this->showResponse(Plate::where('number', $number));
    }

    private function showResponse(Builder $plateQuery): JsonResponse
    {
        $plateModel = $plateQuery
            ->with([
                'markers',
                'markers.markerMedia',
                'markers.markerComments',
            ])
            ->first();

        return response()->json([
            'plate' => fractal($plateModel, new PlateTransformer)
                ->parseIncludes('markers')
                ->toArray(),
        ]);
    }
}
