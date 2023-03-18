<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\MarkerStoreRequest;
use App\Http\Requests\MarkerSubmitContactRequest;
use App\Mail\MarkerContact;
use App\Models\Marker;
use App\Services\MarkerService;
use App\Transformers\MarkerTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class MarkersController extends Controller
{
    public function index(string $lang, string $type): View
    {
        $markersCollection = Marker::where('type', $type)->paginate(15);

        return view('front.markers.index', [
            'markers' => fractal($markersCollection, new MarkerTransformer)->toArray(),
            'type' => $type,
        ]);
    }

    public function show(string $lang, string $type, string $id): View | RedirectResponse
    {
        $markerModel = Marker::find($id);

        if (!$markerModel) {
            return redirect()->route('front.markers.index', ['type' => $type]);
        }

        if ($markerModel->type !== $type) {
            return redirect($markerModel->link());
        }

        return view('front.markers.show', [
            'marker' => fractal($markerModel, new MarkerTransformer)->toArray(),
            'type' => $type,
        ]);
    }

    public function ajaxStore(MarkerService $markerService, MarkerStoreRequest $request): JsonResponse
    {
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

    public function ajaxIndex(Request $request): JsonResponse
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
                ->paginate(15);
        }

        $markers = fractal($markersCollection, new MarkerTransformer)
            ->toArray();

        return response()->json([
            'markers' => $markers,
        ]);
    }

    public function ajaxShow(string $id): JsonResponse
    {
        $markerModel = Marker::findOrFail($id);

        $marker = fractal($markerModel, new MarkerTransformer)
            ->toArray();

        return response()->json([
            'marker' => $marker,
        ]);
    }

    public function ajaxGetPhoneNumber(string $id): JsonResponse
    {
        $marker = Marker::findOrFail($id);

        return response()->json([
            'phone_number' => $marker->formattedPhoneNumber(),
        ]);
    }

    public function ajaxSubmitContact(MarkerSubmitContactRequest $request, string $id): JsonResponse
    {
        $marker = Marker::findOrFail($id);

        if (!$marker->email) {
            throw ValidationException::withMessages([
                'constact' => 'Ten znacznik nie posiada adresu e-mail.',
            ]);
        }

        Mail::to($marker->email)
            ->queue(new MarkerContact($marker, $request->contact));

        return response()->json([
            'message' => 'Wiadomość została wysłana.',
        ]);
    }
}
