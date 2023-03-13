<?php

namespace App\Http\Controllers;

use App\Http\Requests\MarkerCommentStoreRequest;
use App\Models\Marker;
use App\Models\MarkerComment;

class MarkerCommentsController extends Controller
{
    public function ajaxStore(MarkerCommentStoreRequest $request, $markerId)
    {
        $marker = Marker::findOrFail($markerId);

        $marker->markerComments()->create([
            'name' => $request->name,
            'content' => $request->content,
        ]);

        return response()->noContent();
    }
}
