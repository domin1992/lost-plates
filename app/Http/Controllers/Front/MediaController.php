<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Media;
use App\Transformers\MediaTransformer;
use Auth;

class MediaController extends Controller
{
    public function ajaxStore(Request $request)
    {
        $mediaModel = Media::addFromUpload(
            ($request->file('media') != null ? $request->file('media') : $request->image),
            $request->file_type,
            $request->image_type,
            (Auth::check() ? Auth::User()->id : null)
        );

        $media = fractal($mediaModel, new MediaTransformer)
            ->toArray();

        return response()->json([
            'media' => $media
        ]);
    }

    public function ajaxDestroy(Request $request, $id)
    {
        $media = Media::findOrFail($id);

        if(null == $media->user_id || (null != $media->user_id && Auth::check() && Auth::User()->id == $media->user_id)){
            $media->delete();
        }

        return response()->json([
            'success' => true
        ]);
    }
}
