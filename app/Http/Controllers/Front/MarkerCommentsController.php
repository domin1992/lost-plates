<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\MarkerCommentStoreRequest;
use App\Models\Marker;

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
