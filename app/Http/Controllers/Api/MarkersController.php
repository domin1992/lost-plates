<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MarkersIndexRequest;
use App\Http\Requests\MarkersPhoneNumberRequest;
use App\Http\Requests\MarkersStoreRequest;
use App\Http\Requests\MarkerSubmitContactRequest;
use App\Mail\MarkerContact;
use App\Models\Marker;
use App\Services\MarkerService;
use App\Transformers\MarkerTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class MarkersController extends Controller
{
    public function index(
        MarkersIndexRequest $request
    ): JsonResponse {
        $markers = Marker::select('markers.*')
            ->join('plates', 'plates.id', '=', 'markers.plate_id');

        if ($request->plateNumber) {
            $markers = $markers->where('plates.number', 'LIKE', '%' . $request->plateNumber . '%');
        }

        if ($request->corners && !$request->plateNumber) {
            $corners = $request->corners;
            $markers = $markers->where([
                ['markers.lat', '<=', $corners['nwLat']],
                ['markers.lng', '>=', $corners['nwLng']],
                ['markers.lat', '>=', $corners['seLat']],
                ['markers.lng', '<=', $corners['seLng']],
            ]);
        }

        if ($request->has('type')) {
            $markers = $markers->where('markers.type', $request->type);
        }

        if (!$request->has('paginate') && $request->paginate) {
            $markersCollection = $markers
                ->limit(50)
                ->orderBy('markers.created_at', 'desc')
                ->get();
        } else {
            $markersCollection = $markers
                ->orderBy('markers.created_at', 'desc')
                ->paginate(50);
        }

        $markers = fractal($markersCollection, new MarkerTransformer)
            ->toArray();

        return response()->json($markers);
    }

    public function store(
        MarkersStoreRequest $request,
        MarkerService $markerService
    ): JsonResponse {
        $marker = $markerService->store(
            $request->plateNumber,
            $request->type,
            (float)$request->lat,
            (float)$request->lng,
            $request->media ?? [],
            $request->phoneNumber,
            $request->email,
            (float)str_replace(',', '.', $request->radius),
            $request->additionalInfo,
            $request->notifyWhenFound
        );

        if (app()->isProduction()) {
            Log::channel('slack')->info('New marker stored: ' . $marker->link());
        }

        return response()->json([
            'message' => trans('frontMarkersController.markerStored'),
            'marker' => fractal($marker, new MarkerTransformer)
                ->toArray(),
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $marker = Marker::findOrFail($id);

        return response()->json([
            'marker' => fractal($marker, new MarkerTransformer)
                ->toArray(),
        ]);
    }

    public function phoneNumber(MarkersPhoneNumberRequest $request, string $id): JsonResponse
    {
        $marker = Marker::findOrFail($id);

        return response()->json([
            'phoneNumber' => $marker->formattedPhoneNumber(),
        ]);
    }

    public function submitContact(MarkerSubmitContactRequest $request, string $id): JsonResponse
    {
        $marker = Marker::findOrFail($id);

        if (!$marker->email) {
            throw ValidationException::withMessages([
                'contact' => trans('frontMarkersController.thisMarkerHasNoEmailAssigned'),
            ]);
        }

        Mail::to($marker->email)
            ->queue(new MarkerContact($marker, $request->contact));

        return response()->json([
            'message' => trans('frontMarkersController.messageSent'),
        ]);
    }
}
