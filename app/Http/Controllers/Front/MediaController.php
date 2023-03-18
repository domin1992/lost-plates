<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Transformers\MediaTransformer;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function ajaxStore(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file',
            'type' => 'required|string',
            'image_type' => 'required_if:type,image|string',
        ]);

        $media = Media::add($request->file('file'), $request->type, $request->image_type, auth()->check() ? auth()->user()->id : null);

        return response()->json([
            'media' => fractal($media, new MediaTransformer)->toArray(),
        ]);
    }

    public function ajaxDestroy(string $id): JsonResponse
    {
        $media = Media::findOrFail($id);

        if (null == $media->user_id || (null != $media->user_id && Auth::check() && Auth::User()->id == $media->user_id)) {
            $media->delete();
        }

        return response()->json([
            'success' => true,
        ]);
    }
}
