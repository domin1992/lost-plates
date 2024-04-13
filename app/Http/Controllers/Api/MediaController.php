<?php

namespace App\Http\Controllers\Api;

use App\Factories\MediaFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\MediaStoreRequest;
use App\Models\Media;
use App\Transformers\MediaTransformer;
use Illuminate\Http\JsonResponse;

class MediaController extends Controller
{
    public function store(
        MediaFactory $mediaFactory,
        MediaStoreRequest $request
    ): JsonResponse {
        $media = $mediaFactory->addFile(
            $request->file('file'),
            $request->type,
            $request->imageType,
            auth()->check()
                ? auth()->user()->id
                : null
        );

        return response()->json([
            'message' => trans('apiMediaController.mediaStored'),
            'media' => fractal($media, new MediaTransformer)
                ->toArray(),
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $media = Media::findOrFail($id);

        return response()->json([
            'media' => fractal($media, new MediaTransformer)
                ->toArray(),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $media = Media::findOrFail($id);

        if (
            !is_null($media->user_id)
            && (
                !auth()->check()
                || auth()->user()->id !== $media->user_id
            )
        ) {
            return response()->json([
                'message' => trans('common.youHaveNoPermissionToPerformThisAction'),
            ], 403);
        }

        $media->delete();

        return response()->json([
            'message' => trans('apiMediaController.mediaDeleted'),
        ]);
    }
}
