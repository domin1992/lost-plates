<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MarkerCommentStoreRequest;
use App\Models\Marker;
use App\Transformers\MarkerCommentTransformer;
use Illuminate\Http\JsonResponse;

class MarkerCommentsController extends Controller
{
    public function index(string $markerId): JsonResponse
    {
        $marker = Marker::findOrFail($markerId);

        return response()->json([
            'markerComments' => fractal($marker->markerComments, new MarkerCommentTransformer)->toArray(),
        ]);
    }

    public function store(
        MarkerCommentStoreRequest $request,
        string $markerId
    ): JsonResponse {
        $marker = Marker::findOrFail($markerId);

        $markerComment = $marker->markerComments()->create([
            'name' => $request->name,
            'content' => $request->content,
        ]);

        return response()->json([
            'message' => trans('apiMarkerCommentsController.markerCommentStored'),
            'markerComment' => fractal($markerComment, new MarkerCommentTransformer)->toArray(),
        ]);
    }
}
