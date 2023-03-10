<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Marker;
use App\Rules\PhoneNumberValidationRule;
use App\Services\MarkerService;
use App\Transformers\MarkerTransformer;
use Illuminate\Http\Request;

class MarkersController extends Controller
{
    public function ajaxStore(MarkerService $markerService, Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:' . Marker::TYPE_FOUND . ',' . Marker::TYPE_LOST,
            'plate_number' => 'required|string|min:3|max:191',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'phone_number' => ['nullable', 'string', 'max:191', new PhoneNumberValidationRule],
            'email' => 'nullable|email|max:191',
            'radius' => 'nullable|numeric',
            'additional_info' => 'nullable|string',
            'media' => 'nullable|array|max:5',
            'media.*' => 'nullable|string|exists:media,id',
        ]);

        $markerService->store(
            $request->plate_number,
            $request->type,
            (float)$request->lat,
            (float)$request->lng,
            $request->media,
            $request->phone_number,
            $request->email,
            (float)str_replace(',', '.', $request->radius),
            $request->additional_info,
            $request->notify_when_found === 'on'
        );

        return response()->json([
            'message' => 'Pineska została dodana do mapy.',
        ]);
    }

    public function ajaxIndex(Request $request)
    {
        $markers = Marker::select('markers.*')
            ->join('plates', 'plates.id', '=', 'markers.plate_id');

        if ($request->plate_number) {
            $markers = $markers->where('plates.number', 'LIKE', '%' . $request->plate_number . '%');
        }

        if ($request->corners && !$request->plate_number) {
            $corners = $request->corners;
            $markers = $markers->where([
                ['markers.lat', '<=', $corners['nw_lat']],
                ['markers.lng', '>=', $corners['nw_lng']],
                ['markers.lat', '>=', $corners['se_lat']],
                ['markers.lng', '<=', $corners['se_lng']],
            ]);
        }

        $markersCollection = $markers
            ->limit(50)
            ->orderBy('created_at', 'desc')
            ->get();
        $markers = fractal($markersCollection, new MarkerTransformer)
            ->toArray();

        return response()->json([
            'markers' => $markers,
        ]);
    }

    public function ajaxShow(Request $request, $id)
    {
        $markerModel = Marker::findOrFail($id);

        $marker = fractal($markerModel, new MarkerTransformer)
            ->toArray();

        return response()->json([
            'marker' => $marker,
        ]);
    }

    public function ajaxGetPhoneNumber(Request $request, $id)
    {
        $marker = Marker::findOrFail($id);

        return response()->json([
            'phone_number' => $marker->formattedPhoneNumber(),
        ]);
    }
}
